<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\OrlandoBiblio;
use App\Repository\OrlandoBiblioRepository;
use App\Services\OrlandoManager;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * OrlandoBiblio controller.
 *
 * @Security("is_granted('ROLE_USER')")
 * @Route("/resource/orlando_biblio")
 */
class OrlandoBiblioController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all OrlandoBiblio entities.
     *
     * @Route("/", name="resource_orlando_biblio_index", methods={"GET"})
     * @Template
     */
    public function indexAction(Request $request, OrlandoManager $manager, OrlandoBiblioRepository $repository) : array {
        $pageSize = $this->getParameter('page_size');
        $query = $repository->indexQuery();
        $orlandoBiblios = $this->paginator->paginate($query, $request->query->getInt('page', 1), $pageSize);

        return [
            'orlandoBiblios' => $orlandoBiblios,
            'manager' => $manager,
        ];
    }

    /**
     * Search for OrlandoBiblio entities.
     *
     * @Route("/search", name="resource_orlando_biblio_search", methods={"GET"})
     * @Template
     */
    public function searchAction(Request $request, OrlandoManager $manager, OrlandoBiblioRepository $repo) : array {
        $pageSize = $this->getParameter('page_size');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $orlandoBiblios = $this->paginator->paginate($query, $request->query->getInt('page', 1), $pageSize);
        } else {
            $orlandoBiblios = [];
        }

        return [
            'orlandoBiblios' => $orlandoBiblios,
            'q' => $q,
            'manager' => $manager,
        ];
    }

    /**
     * Finds and displays a OrlandoBiblio entity.
     *
     * @Route("/{id}", name="resource_orlando_biblio_show", methods={"GET"})
     * @Template
     */
    public function showAction(OrlandoBiblio $orlandoBiblio, OrlandoManager $manager) : array {
        return [
            'orlandoBiblio' => $orlandoBiblio,
            'manager' => $manager,
        ];
    }
}
