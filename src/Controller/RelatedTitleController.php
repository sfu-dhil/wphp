<?php

namespace App\Controller;

use App\Entity\RelatedTitle;
use App\Form\RelatedTitleType;
use App\Repository\RelatedTitleRepository;

use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/related_title")
 * @IsGranted("ROLE_USER")
 */
class RelatedTitleController extends AbstractController implements PaginatorAwareInterface
{
    use PaginatorTrait;

    /**
     * @Route("/", name="related_title_index", methods={"GET"})
     * @param Request $request
     * @param RelatedTitleRepository $relatedTitleRepository
     *
     * @Template()
     *
     * @return array
     */
    public function index(Request $request, RelatedTitleRepository $relatedTitleRepository) : array
    {
        $query = $relatedTitleRepository->indexQuery();
        $pageSize = $this->getParameter('page_size');
        $page = $request->query->getint('page', 1);

        return [
            'related_titles' => $this->paginator->paginate($query, $page, $pageSize),
        ];
    }

    /**
     * @Route("/{id}", name="related_title_show", methods={"GET"})
     * @Template()
     * @param RelatedTitle $relatedTitle
     *
     * @return array
     */
    public function show(RelatedTitle $relatedTitle) {
        return [
            'related_title' => $relatedTitle,
        ];
    }

}
