<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\AasMarc;
use App\Repository\AasMarcRepository;
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
 * AasMarc controller.
 *
 * @Security("is_granted('ROLE_USER')")
 * @Route("/resource/aas")
 */
class AasMarcController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all AasMarc entities.
     *
     * @Route("/", name="resource_aas_index", methods={"GET"})
     * @Template
     */
    public function indexAction(Request $request, MarcManager $manager, AasMarcRepository $repo) : array {
        $pageSize = $this->getParameter('page_size');
        $query = $repo->indexQuery();
        $aasMarcs = $this->paginator->paginate($query, $request->query->getInt('page', 1), $pageSize);

        return [
            'aasMarcs' => $aasMarcs,
            'manager' => $manager,
        ];
    }

    /**
     * Search for AasMarc entities.
     *
     * @Route("/search", name="resource_aas_search", methods={"GET"})
     * @Template
     */
    public function searchAction(Request $request, MarcManager $manager, AasMarcRepository $repo) : array {
        $pageSize = $this->getParameter('page_size');
        $q = $request->query->get('q');
        if ($q) {
            $result = $repo->searchQuery($q);
            $titleIds = $this->paginator->paginate($result, $request->query->getInt('page', 1), $pageSize);
        } else {
            $titleIds = [];
        }
        $aasMarcs = [];

        foreach ($titleIds as $titleId) {
            $aasMarcs[] = $repo->findOneBy([
                'titleId' => $titleId,
                'field' => 'ldr',
            ]);
        }

        return [
            'titleIds' => $titleIds,
            'aasMarcs' => $aasMarcs,
            'q' => $q,
            'manager' => $manager,
        ];
    }

    /**
     * Search for AasMarc entities.
     *
     * @Route("/imprint_search", name="resource_aas_search_imprint", methods={"GET"})
     * @Template
     */
    public function imprintSearchAction(Request $request, MarcManager $manager, AasMarcRepository $repo) : array {
        $pageSize = $this->getParameter('page_size');
        $q = $request->query->get('q');
        if ($q) {
            $result = $repo->imprintSearchQuery($q);
            $titleIds = $this->paginator->paginate($result, $request->query->getInt('page', 1), $pageSize);
        } else {
            $titleIds = [];
        }
        $aasMarcs = [];

        foreach ($titleIds as $titleId) {
            $aasMarcs[] = $repo->findOneBy([
                'titleId' => $titleId,
                'field' => 'ldr',
            ]);
        }

        return [
            'titleIds' => $titleIds,
            'aasMarcs' => $aasMarcs,
            'q' => $q,
            'manager' => $manager,
        ];
    }

    /**
     * Finds and displays a AasMarc entity.
     *
     * @Route("/{id}", name="resource_aas_show", methods={"GET"})
     * @ParamConverter("aasMarc", options={"mapping" = {"id" = "titleId"}})
     * @Template
     */
    public function showAction(AasMarc $aasMarc, MarcManager $manager) : array {
        return [
            'aasMarc' => $aasMarc,
            'manager' => $manager,
        ];
    }
}
