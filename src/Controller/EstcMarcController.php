<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\EstcMarc;
use App\Repository\EstcMarcRepository;
use App\Services\MarcManager;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * EstcMarc controller.
 */
#[Security("is_granted('ROLE_USER')")]
#[Route(path: '/resource/estc')]
class EstcMarcController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all EstcMarc entities.
     *
     * @return array<string,mixed> *
     */
    #[Route(path: '/', name: 'resource_estc_index', methods: ['GET'])]
    #[Template]
    public function indexAction(Request $request, MarcManager $manager, EstcMarcRepository $repo) {
        $query = $repo->indexQuery();
        $estcMarcs = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'estcMarcs' => $estcMarcs,
            'manager' => $manager,
        ];
    }

    /**
     * Search for EstcMarc entities.
     *
     * @return array<string,mixed> * @Route("/search", name="resource_estc_search", methods={"GET"})
     */
    #[Template]
    public function searchAction(Request $request, MarcManager $manager, EstcMarcRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $result = $repo->searchQuery($q);
            $titleIds = $this->paginator->paginate($result, $request->query->getInt('page', 1), 25);
        } else {
            $titleIds = [];
        }
        $estcMarcs = [];

        foreach ($titleIds as $titleId) {
            $estcMarcs[] = $repo->findOneBy([
                'titleId' => $titleId,
                'field' => 'ldr',
            ]);
        }

        return [
            'titleIds' => $titleIds,
            'estcMarcs' => $estcMarcs,
            'q' => $q,
            'manager' => $manager,
        ];
    }

    /**
     * Search for EstcMarc entities.
     *
     * @return array<string,mixed> * @Route("/imprint_search", name="resource_estc_search_imprint", methods={"GET"})
     */
    #[Template]
    public function imprintSearchAction(Request $request, MarcManager $manager, EstcMarcRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $result = $repo->imprintSearchQuery($q);
            $titleIds = $this->paginator->paginate($result, $request->query->getInt('page', 1), 25);
        } else {
            $titleIds = [];
        }
        $estcMarcs = [];

        foreach ($titleIds as $titleId) {
            $estcMarcs[] = $repo->findOneBy([
                'titleId' => $titleId,
                'field' => 'ldr',
            ]);
        }

        return [
            'titleIds' => $titleIds,
            'estcMarcs' => $estcMarcs,
            'q' => $q,
            'manager' => $manager,
        ];
    }

    /**
     * Finds and displays a EstcMarc entity.
     *
     * @return array<string,mixed> *
     */
    #[Route(path: '/{id}', name: 'resource_estc_show', methods: ['GET'])]
    #[ParamConverter('estcMarc', options: ['mapping' => ['id' => 'titleId']])]
    #[Template]
    public function showAction(EstcMarc $estcMarc, MarcManager $manager) {
        return [
            'estcMarc' => $estcMarc,
            'manager' => $manager,
        ];
    }
}
