<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\OsborneMarc;
use App\Repository\OsborneMarcRepository;
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
 * OsborneMarc controller.
 */
#[Security("is_granted('ROLE_USER')")]
#[Route(path: '/resource/osborne')]
class OsborneMarcController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all OsborneMarc entities.
     *
     * @return array<string,mixed> *
     */
    #[Route(path: '/', name: 'resource_osborne_index', methods: ['GET'])]
    #[Template]
    public function indexAction(Request $request, MarcManager $manager, OsborneMarcRepository $repo) {
        $query = $repo->indexQuery();
        $osborneMarcs = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'osborneMarcs' => $osborneMarcs,
            'manager' => $manager,
        ];
    }

    /**
     * Search for OsborneMarc entities.
     *
     * @return array<string,mixed>     */
    #[Route(path: '/search', name: 'resource_osborne_search', methods: ['GET'])]
    #[Template]
    public function searchAction(Request $request, MarcManager $manager, OsborneMarcRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $result = $repo->searchQuery($q);
            $titleIds = $this->paginator->paginate($result, $request->query->getInt('page', 1), 25);
        } else {
            $titleIds = [];
        }
        $osborneMarcs = [];

        foreach ($titleIds as $titleId) {
            $osborneMarcs[] = $repo->findOneBy([
                'titleId' => $titleId,
                'field' => 'ldr',
            ]);
        }

        return [
            'titleIds' => $titleIds,
            'osborneMarcs' => $osborneMarcs,
            'q' => $q,
            'manager' => $manager,
        ];
    }

    /**
     * Finds and displays a OsborneMarc entity.
     *
     * @return array<string,mixed> *
     */
    #[Route(path: '/{id}', name: 'resource_osborne_show', methods: ['GET'])]
    #[ParamConverter('osborneMarc', options: ['mapping' => ['id' => 'titleId']])]
    #[Template]
    public function showAction(OsborneMarc $osborneMarc, MarcManager $manager) {
        return [
            'osborneMarc' => $osborneMarc,
            'manager' => $manager,
        ];
    }
}
