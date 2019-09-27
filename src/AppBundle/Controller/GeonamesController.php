<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Geonames;
use AppBundle\Repository\GeonamesRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

use GeoNames\Client as GeoNamesClient;

/**
 * Geonames controller.
 *
 * @Route("/geonames")
 */
class GeonamesController extends Controller  implements PaginatorAwareInterface {

    use PaginatorTrait;

    /**
     * Lists all Geonames entities.
     *
     * @Route("/", name="geonames_index", methods={"GET"})
     * @Template()
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Geonames e ORDER BY e.geonameid';
        $query = $em->createQuery($dql);
        $geonames = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return array(
            'geonames' => $geonames,
        );
    }

    /**
     * Typeahead action for editor widgets.
     *
     * @param Request $request
     * @param GeonamesRepository $repo
     *
     * @return JsonResponse
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Route("/typeahead", name="geonames_typeahead", methods={"GET"})
     */
    public function typeaheadAction(Request $request, GeonamesRepository $repo) {
        $q = $request->query->get('q');
        if( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];
        foreach($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getGeonameid(),
                'text' => $result->getName() . ' (' . $result->getCountry() . ')',
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Search for geonames entities.
     *
     * @param Request $request
     * @param GeonamesRepository $repo
     *
     * @return array
     * @Route("/search", name="geonames_search", methods={"GET"})
     * @Template()
     *
     */
    public function searchAction(Request $request, GeonamesRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $geonames = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $geonames = array();
        }

        return array(
            'geonames' => $geonames,
            'q' => $q,
        );
    }

    /**
     * Search and display results from the Geonames API in preparation for import.
     *
     * @param Request $request
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Route("/import", name="geonames_import", methods={"GET"})
     *
     * @Template
     * @return array
     */
    public function importSearchAction(Request $request) {
        $q = $request->query->get('q');
        $results = array();
        if($q) {
            $user = $this->getParameter('wphp.geonames_user');
            $client = new GeoNamesClient($user);
            $results = $client->search(array(
                'name' => $q,
                'fcl' => array('A', 'P'),
                'lang' => 'en',
            ));
        }
        return [
            'q' => $q,
            'results' => $results,
        ];
    }

    /**
     * Import one or more search results from the Geonames API.
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return RedirectResponse
     * @throws \Exception
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Route("/import", name="geonames_import_save", methods={"POST"})
     *
     */
    public function importSaveAction(Request $request, EntityManagerInterface $em) {
        $user = $this->getParameter('wphp.geonames_user');
        $client = new GeoNamesClient($user);
        $repo = $em->getRepository(Geonames::class);
        foreach($request->request->get('geonameid') as $geonameid) {
            $data = $client->get(array(
                'geonameId' => $geonameid,
                'lang' => 'en',
            ));
            if($repo->find($geonameid)) {
                $this->addFlash('warning', "Geoname #{$geonameid} ({$data->asciiName}) is already in the database.");
                continue;
            }
            $geoname = new Geonames();
            $geoname->setGeonameid($data->geonameId);
            $geoname->setName($data->name);
            $geoname->setAsciiname($data->asciiName);
            $alternateNames = array();
            foreach($data->alternateNames as $name) {
                if(isset($name->lang) && $name->lang != 'en') {
                    continue;
                }
                $alternateNames[] = $name->name;
            }
            $geoname->setAlternatenames(implode(", ", $alternateNames));
            $geoname->setLatitude($data->lat);
            $geoname->setLongitude($data->lng);
            $geoname->setFclass($data->fcl);
            $geoname->setFcode($data->fcode);
            $geoname->setCountry($data->countryCode);
            $geoname->setPopulation($data->population);
            $geoname->setTimezone($data->timezone->timeZoneId);
            $geoname->setModdate(new DateTime());
            $em->persist($geoname);
        }
        $em->flush();
        $this->addFlash("success", "The selected geonames have been imported.");
        return $this->redirectToRoute('geonames_import', array($request->query->get('q')));
    }

    /**
     * Finds and displays a Geonames entity.
     *
     * @Route("/{id}", name="geonames_show", methods={"GET"})
     * @Template()
     * @param Request $request
     * @param Geonames $geoname
     *
     * @return array
     */
    public function showAction(Request $request, Geonames $geoname) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT count(t.id) FROM AppBundle:Title t WHERE t.locationOfPrinting = :geoname';
        if($this->getUser() === null) {
            $dql .= ' AND (t.finalcheck = 1 OR t.finalattempt = 1)';
        }
        $dql .= ' ORDER BY t.title';
        $query = $em->createQuery($dql);
        $query->setParameter('geoname', $geoname);
        $count = $query->getSingleScalarResult();

        return array(
            'geoname' => $geoname,
            'count' => $count,
        );
    }

    /**
     * Finds and displays a Geonames entity.
     *
     * @Route("/{id}/titles", name="geonames_titles", methods={"GET"})
     * @Template()
     * @param Request $request
     * @param Geonames $geoname
     *
     * @return array
     */
    public function titlesAction(Request $request, Geonames $geoname) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT t FROM AppBundle:Title t WHERE t.locationOfPrinting = :geoname';
        if($this->getUser() === null) {
            $dql .= ' AND (t.finalcheck = 1 OR t.finalattempt = 1)';
        }
        $dql .= ' ORDER BY t.title';
        $query = $em->createQuery($dql);
        $query->setParameter('geoname', $geoname);
        $titles = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return array(
            'geoname' => $geoname,
            'titles' => $titles,
        );
    }

    /**
     * Finds and displays a Geonames entity.
     *
     * @Route("/{id}/firms", name="geonames_firms", methods={"GET"})
     * @Template()
     * @param Request $request
     * @param Geonames $geoname
     *
     * @return array
     */
    public function firmsAction(Request $request, Geonames $geoname) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT f FROM AppBundle:Firm f WHERE f.city = :geoname ORDER BY f.name';
        $query = $em->createQuery($dql);
        $query->setParameter('geoname', $geoname);
        $firms = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return array(
            'geoname' => $geoname,
            'firms' => $firms,
        );
    }

    /**
     * Finds and displays a Geonames entity.
     *
     * @Route("/{id}/people", name="geonames_people", methods={"GET"})
     * @Template()
     * @param Request $request
     * @param Geonames $geoname
     *
     * @return array
     */
    public function peopleAction(Request $request, Geonames $geoname) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT p FROM AppBundle:Person p WHERE (p.cityOfBirth = :geoname) OR (p.cityOfDeath = :geoname) ORDER BY p.lastName, p.firstName';
        $query = $em->createQuery($dql);
        $query->setParameter('geoname', $geoname);
        $people = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return array(
            'geoname' => $geoname,
            'people' => $people,
        );
    }

}
