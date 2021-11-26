<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Jackson;
use App\Repository\JacksonRepository;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Jackson controller.
 *
 * @Security("is_granted('ROLE_USER')")
 * @Route("/resource/jackson")
 */
class JacksonController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Jackson entities.
     *
     * @Route("/", name="resource_jackson_index", methods={"GET"})
     *
     * @Template
     */
    public function indexAction(Request $request, JacksonRepository $repository) : array {
        $pageSize = $this->getParameter('page_size');
        $query = $repository->indexQuery();
        $jacksons = $this->paginator->paginate($query, $request->query->getInt('page', 1), $pageSize);

        return [
            'jacksons' => $jacksons,
        ];
    }

    /**
     * Search for Jackson entities.
     *
     * @Route("/search", name="resource_jackson_search", methods={"GET"})
     * @Template
     */
    public function searchAction(Request $request, JacksonRepository $repo) : array {
        $pageSize = $this->getParameter('page_size');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $jacksons = $this->paginator->paginate($query, $request->query->getInt('page', 1), $pageSize);
        } else {
            $jacksons = [];
        }

        return [
            'jacksons' => $jacksons,
            'q' => $q,
        ];
    }

    /**
     * Finds and displays a Jackson entity.
     *
     * @Route("/{id}", name="resource_jackson_show", methods={"GET"})
     *
     * @Template
     */
    public function showAction(Jackson $jackson) : array {
        return [
            'jackson' => $jackson,
        ];
    }
}
