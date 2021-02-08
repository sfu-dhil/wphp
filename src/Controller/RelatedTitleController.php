<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\RelatedTitle;
use App\Repository\RelatedTitleRepository;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/related_title")
 * @IsGranted("ROLE_USER")
 */
class RelatedTitleController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @Route("/", name="related_title_index", methods={"GET"})
     *
     * @Template
     */
    public function index(Request $request, RelatedTitleRepository $relatedTitleRepository) : array {
        $query = $relatedTitleRepository->indexQuery();
        $pageSize = $this->getParameter('page_size');
        $page = $request->query->getint('page', 1);

        return [
            'related_titles' => $this->paginator->paginate($query, $page, $pageSize),
        ];
    }

    /**
     * @Route("/{id}", name="related_title_show", methods={"GET"})
     * @Template
     *
     * @return array
     */
    public function show(RelatedTitle $relatedTitle) {
        return [
            'related_title' => $relatedTitle,
        ];
    }
}
