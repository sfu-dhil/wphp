<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\En;
use App\Repository\EnRepository;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * En controller.
 *
 * @Security("is_granted('ROLE_USER')")
 * @Route("/resource/en")
 */
class EnController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all En entities.
     *
     * @Route("/", name="resource_en_index", methods={"GET"})
     * @Template
     */
    public function indexAction(Request $request, EnRepository $repository) : array {
        $pageSize = $this->getParameter('page_size');
        $query = $repository->indexQuery();
        $ens = $this->paginator->paginate($query, $request->query->getInt('page', 1), $pageSize);

        return [
            'ens' => $ens,
        ];
    }

    /**
     * Search for En entities.
     *
     * @Route("/search", name="resource_en_search", methods={"GET"})
     * @Template
     */
    public function searchAction(Request $request, EnRepository $repo) : array {
        $pageSize = $this->getParameter('page_size');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $ens = $this->paginator->paginate($query, $request->query->getInt('page', 1), $pageSize);
        } else {
            $ens = [];
        }

        return [
            'ens' => $ens,
            'q' => $q,
        ];
    }

    /**
     * Finds and displays a En entity.
     *
     * @Route("/{id}", name="resource_en_show", methods={"GET"})
     *
     * @Template
     */
    public function showAction(En $en) : array {
        return [
            'en' => $en,
        ];
    }
}
