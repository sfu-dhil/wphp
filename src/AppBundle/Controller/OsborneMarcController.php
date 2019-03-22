<?php

namespace AppBundle\Controller;

use AppBundle\Entity\OsborneMarc;
use AppBundle\Repository\OsborneMarcRepository;
use AppBundle\Services\MarcManager;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * OsborneMarc controller.
 *
 * @Security("has_role('ROLE_USER')")
 * @Route("/resource/osborne")
 */
class OsborneMarcController extends Controller  implements PaginatorAwareInterface {

    use PaginatorTrait;

    /**
     * Lists all OsborneMarc entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="resource_osborne_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request, MarcManager $manager, OsborneMarcRepository $repo) {
        $query = $repo->indexQuery();
        $osborneMarcs = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return array(
            'osborneMarcs' => $osborneMarcs,
            'manager' => $manager,
        );
    }

    /**
     * Search for OsborneMarc entities.
     *
     * @param Request $request
     *
     * @Route("/search", name="resource_osborne_search")
     * @Method("GET")
     * @Template()
     */
    public function searchAction(Request $request, MarcManager $manager, OsborneMarcRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $result = $repo->searchQuery($q);
                $titleIds = $this->paginator->paginate($result, $request->query->getInt('page', 1), 25);
        } else {
            $titleIds = array();
        }
        $osborneMarcs = array();
        foreach($titleIds as $titleId) {
            $osborneMarcs[] = $repo->findOneBy(array(
                'titleId' => $titleId,
                'field' => 'ldr',
            ));
        }
        return array(
            'titleIds' => $titleIds,
            'osborneMarcs' => $osborneMarcs,
            'q' => $q,
            'manager' => $manager,
        );
    }

    /**
     * Finds and displays a OsborneMarc entity.
     *
     * @param OsborneMarc $osborneMarc
     *
     * @return array
     *
     * @Route("/{id}", name="resource_osborne_show")
     * @Method("GET")
     * @ParamConverter("osborneMarc", options={"mapping": {"id": "titleId"}})
     * @Template()
     */
    public function showAction(OsborneMarc $osborneMarc, MarcManager $manager) {

        return array(
            'osborneMarc' => $osborneMarc,
            'manager' => $manager
        );
    }

}
