<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Geonames;
use App\Repository\GeonamesRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use GeoNames\Client as GeoNamesClient;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Geonames controller.
 *
 * @Route("/geonames")
 */
class GeonamesController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Geonames entities.
     *
     * @Route("/", name="geonames_index", methods={"GET"})
     * @Template()
     *
     * @return array
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:Geonames e ORDER BY e.geonameid';
        $query = $em->createQuery($dql);
        $geonames = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'geonames' => $geonames,
        ];
    }

    /**
     * Typeahead action for editor widgets.
     *
     * @return JsonResponse
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     * @Route("/typeahead", name="geonames_typeahead", methods={"GET"})
     */
    public function typeaheadAction(Request $request, GeonamesRepository $repo) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];
        foreach ($repo->typeaheadQuery($q) as $result) {
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
     * @return array
     * @Route("/search", name="geonames_search", methods={"GET"})
     * @Template()
     */
    public function searchAction(Request $request, GeonamesRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $geonames = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $geonames = [];
        }

        return [
            'geonames' => $geonames,
            'q' => $q,
        ];
    }

    /**
     * Search and display results from the Geonames API in preparation for import.
     *
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     * @Route("/import", name="geonames_import", methods={"GET"})
     *
     * @Template
     *
     * @return array
     */
    public function importSearchAction(Request $request) {
        $q = $request->query->get('q');
        $results = [];
        if ($q) {
            $user = $this->getParameter('wphp.geonames.username');
            $client = new GeoNamesClient($user);
            $results = $client->search([
                'name' => $q,
                'fcl' => ['A', 'P'],
                'lang' => 'en',
            ]);
        }

        return [
            'q' => $q,
            'results' => $results,
        ];
    }

    /**
     * Import one or more search results from the Geonames API.
     *
     * @throws Exception
     *
     * @return RedirectResponse
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     * @Route("/import", name="geonames_import_save", methods={"POST"})
     */
    public function importSaveAction(Request $request, EntityManagerInterface $em) {
        $user = $this->getParameter('wphp.geonames.username');
        $client = new GeoNamesClient($user);
        $repo = $em->getRepository(Geonames::class);
        foreach ($request->request->get('geonameid') as $geonameid) {
            $data = $client->get([
                'geonameId' => $geonameid,
                'lang' => 'en',
            ]);
            if ($repo->find($geonameid)) {
                $this->addFlash('warning', "Geoname #{$geonameid} ({$data->asciiName}) is already in the database.");

                continue;
            }
            $geoname = new Geonames();
            $geoname->setGeonameid($data->geonameId);
            $geoname->setName($data->name);
            $geoname->setAsciiname($data->asciiName);
            $alternateNames = [];
            foreach ($data->alternateNames as $name) {
                if (isset($name->lang) && 'en' !== $name->lang) {
                    continue;
                }
                $alternateNames[] = $name->name;
            }
            $geoname->setAlternatenames(implode(', ', $alternateNames));
            $geoname->setLatitude($data->lat);
            $geoname->setLongitude($data->lng);
            $geoname->setFclass($data->fcl);
            $geoname->setFcode($data->fcode);
            if(isset($data->countryCode)) {
                $geoname->setCountry($data->countryCode);
            }
            $geoname->setPopulation($data->population);
            $geoname->setTimezone($data->timezone->timeZoneId);
            $geoname->setModdate(new DateTime());
            $em->persist($geoname);
        }
        $em->flush();
        $this->addFlash('success', 'The selected geonames have been imported.');

        return $this->redirectToRoute('geonames_import', [$request->query->get('q')]);
    }

    /**
     * Finds and displays a Geonames entity.
     *
     * @Route("/{id}", name="geonames_show", methods={"GET"})
     * @Template()
     *
     * @return array
     */
    public function showAction(Request $request, Geonames $geoname, EntityManagerInterface $em) {
        $dql = 'SELECT count(t.id) FROM App:Title t WHERE t.locationOfPrinting = :geoname';
        if (null === $this->getUser()) {
            $dql .= ' AND (t.finalcheck = 1 OR t.finalattempt = 1)';
        }
        $dql .= ' ORDER BY t.title';
        $query = $em->createQuery($dql);
        $query->setParameter('geoname', $geoname);
        $count = $query->getSingleScalarResult();

        return [
            'geoname' => $geoname,
            'count' => $count,
        ];
    }

    /**
     * Finds and displays a Geonames entity.
     *
     * @Route("/{id}/titles", name="geonames_titles", methods={"GET"})
     * @Template()
     *
     * @return array
     */
    public function titlesAction(Request $request, Geonames $geoname, EntityManagerInterface $em) {
        $dql = 'SELECT t FROM App:Title t WHERE t.locationOfPrinting = :geoname';
        if (null === $this->getUser()) {
            $dql .= ' AND (t.finalcheck = 1 OR t.finalattempt = 1)';
        }
        $dql .= ' ORDER BY t.title';
        $query = $em->createQuery($dql);
        $query->setParameter('geoname', $geoname);
        $titles = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'geoname' => $geoname,
            'titles' => $titles,
        ];
    }

    /**
     * Finds and displays a Geonames entity.
     *
     * @Route("/{id}/firms", name="geonames_firms", methods={"GET"})
     * @Template()
     *
     * @return array
     */
    public function firmsAction(Request $request, Geonames $geoname, EntityManagerInterface $em) {
        $dql = 'SELECT f FROM App:Firm f WHERE f.city = :geoname ORDER BY f.name';
        $query = $em->createQuery($dql);
        $query->setParameter('geoname', $geoname);
        $firms = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'geoname' => $geoname,
            'firms' => $firms,
        ];
    }

    /**
     * Finds and displays a Geonames entity.
     *
     * @Route("/{id}/people", name="geonames_people", methods={"GET"})
     * @Template()
     *
     * @return array
     */
    public function peopleAction(Request $request, Geonames $geoname, EntityManagerInterface $em) {
        $dql = 'SELECT p FROM App:Person p WHERE (p.cityOfBirth = :geoname) OR (p.cityOfDeath = :geoname) ORDER BY p.lastName, p.firstName';
        $query = $em->createQuery($dql);
        $query->setParameter('geoname', $geoname);
        $people = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'geoname' => $geoname,
            'people' => $people,
        ];
    }
}
