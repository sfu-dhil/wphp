<?php

namespace App\Controller;

use App\Entity\TitleRelationship;
use App\Form\TitleRelationshipType;
use App\Repository\TitleRelationshipRepository;

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
 * @Route("/title_relationship")
 * @IsGranted("ROLE_USER")
 */
class TitleRelationshipController extends AbstractController implements PaginatorAwareInterface
{
    use PaginatorTrait;

    /**
     * @Route("/", name="title_relationship_index", methods={"GET"})
     * @param Request $request
     * @param TitleRelationshipRepository $titleRelationshipRepository
     *
     * @Template()
     *
     * @return array
     */
    public function index(Request $request, TitleRelationshipRepository $titleRelationshipRepository) : array
    {
        $query = $titleRelationshipRepository->indexQuery();
        $pageSize = $this->getParameter('page_size');
        $page = $request->query->getint('page', 1);

        return [
            'title_relationships' => $this->paginator->paginate($query, $page, $pageSize),
        ];
    }

    /**
     * @Route("/typeahead", name="title_relationship_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request, TitleRelationshipRepository $titleRelationshipRepository) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];
        foreach ($titleRelationshipRepository->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string)$result,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/new", name="title_relationship_new", methods={"GET","POST"})
     * @Template()
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function new(Request $request) {
        $titleRelationship = new TitleRelationship();
        $form = $this->createForm(TitleRelationshipType::class, $titleRelationship);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($titleRelationship);
            $entityManager->flush();
            $this->addFlash('success', 'The new title relationship has been saved.');

            return $this->redirectToRoute('title_relationship_show', ['id' => $titleRelationship->getId()]);
        }

        return [
            'title_relationship' => $titleRelationship,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/new_popup", name="title_relationship_new_popup", methods={"GET","POST"})
     * @Template()
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function new_popup(Request $request) {
        return $this->new($request);
    }

    /**
     * @Route("/{id}", name="title_relationship_show", methods={"GET"})
     * @Template()
     * @param TitleRelationship $titleRelationship
     *
     * @return array
     */
    public function show(TitleRelationship $titleRelationship) {
        return [
            'title_relationship' => $titleRelationship,
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="title_relationship_edit", methods={"GET","POST"})
     * @param Request $request
     * @param TitleRelationship $titleRelationship
     *
     * @Template()
     *
     * @return array|RedirectResponse
     */
    public function edit(Request $request, TitleRelationship $titleRelationship) {
        $form = $this->createForm(TitleRelationshipType::class, $titleRelationship);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'The updated title relationship has been saved.');

            return $this->redirectToRoute('title_relationship_show', ['id' => $titleRelationship->getId()]);
        }

        return [
            'title_relationship' => $titleRelationship,
            'form' => $form->createView()
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}", name="title_relationship_delete", methods={"DELETE"})
     * @param Request $request
     * @param TitleRelationship $titleRelationship
     *
     * @return RedirectResponse
     */
    public function delete(Request $request, TitleRelationship $titleRelationship) {
        if ($this->isCsrfTokenValid('delete' . $titleRelationship->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($titleRelationship);
            $entityManager->flush();
            $this->addFlash('success', 'The titleRelationship has been deleted.');
        }

        return $this->redirectToRoute('title_relationship_index');
    }
}
