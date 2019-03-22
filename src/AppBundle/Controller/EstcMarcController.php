<?php

namespace AppBundle\Controller;

use AppBundle\Entity\EstcMarc;
use AppBundle\Repository\EstcMarcRepository;
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
 * EstcMarc controller.
 *
 * @Security("has_role('ROLE_USER')")
 * @Route("/resource/estc")
 */
class EstcMarcController extends Controller  implements PaginatorAwareInterface {

    use PaginatorTrait;


    /**
     * Lists all EstcMarc entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="resource_estc_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request, MarcManager $manager, EstcMarcRepository $repo) {
        $query = $repo->indexQuery();
        $estcMarcs = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return array(
            'estcMarcs' => $estcMarcs,
            'manager' => $manager,
        );
    }

    /**
     * Search for EstcMarc entities.
     *
     * @param Request $request
     *
     * @Route("/search", name="resource_estc_search")
     * @Method("GET")
     * @Template()
     */
    public function searchAction(Request $request, MarcManager $manager, EstcMarcRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $result = $repo->searchQuery($q);
                $titleIds = $this->paginator->paginate($result, $request->query->getInt('page', 1), 25);
        } else {
            $titleIds = array();
        }
        $estcMarcs = array();
        foreach($titleIds as $titleId) {
            $estcMarcs[] = $repo->findOneBy(array(
                'titleId' => $titleId,
                'field' => 'ldr',
            ));
        }
        return array(
            'titleIds' => $titleIds,
            'estcMarcs' => $estcMarcs,
            'q' => $q,
            'manager' => $manager,
        );
    }

    /**
     * Finds and displays a EstcMarc entity.
     *
     * @param EstcMarc $estcMarc
     *
     * @return array
     *
     * @Route("/{id}", name="resource_estc_show")
     * @Method("GET")
     * @ParamConverter("estcMarc", options={"mapping": {"id": "titleId"}})
     * @Template()
     */
    public function showAction(EstcMarc $estcMarc, MarcManager $manager) {

        return array(
            'estcMarc' => $estcMarc,
            'manager' => $manager,
        );
    }

}
