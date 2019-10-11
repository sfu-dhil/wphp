<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Source;
use AppBundle\Form\SourceType;
use AppBundle\Repository\SourceRepository;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Source controller.
 *
 * @Route("/source")
 */
class SourceController extends Controller implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Source entities.
     *
     * @Route("/", name="source_index", methods={"GET"})
     * @Template()
     *
     * @param Request $request
     * @param SourceRepository $repo
     *
     * @return array
     */
    public function indexAction(Request $request, SourceRepository $repo) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Source e ORDER BY e.name';
        $query = $em->createQuery($dql);
        $sources = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return array(
            'sources' => $sources,
            'repo' => $repo,
        );
    }

    /**
     * Typeahead action for editor widgets.
     *
     * @param Request $request
     * @param SourceRepository $repo
     *
     * @return JsonResponse
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Route("/typeahead", name="source_typeahead", methods={"GET"})
     */
    public function typeaheadAction(Request $request, SourceRepository $repo) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse(array());
        }
        $data = array();
        foreach ($repo->typeaheadQuery($q) as $result) {
            $data[] = array(
                'id' => $result->getId(),
                'text' => $result->getName(),
            );
        }

        return new JsonResponse($data);
    }

    /**
     * Creates a new Source entity.
     *
     * @Route("/new", name="source_new", methods={"GET","POST"})
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Template()
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function newAction(Request $request) {
        $source = new Source();
        $form = $this->createForm(SourceType::class, $source);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($source);
            $em->flush();

            $this->addFlash('success', 'The new source was created.');

            return $this->redirectToRoute('source_show', array('id' => $source->getId()));
        }

        return array(
            'source' => $source,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Source entity.
     *
     * @Route("/{id}", name="source_show", methods={"GET"})
     * @Template()
     *
     * @param Request $request
     * @param Source $source
     *
     * @return array
     */
    public function showAction(Request $request, Source $source) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT t FROM AppBundle:Title t INNER JOIN t.titleSources ts WHERE ts.source = :source ORDER BY t.title';
        $query = $em->createQuery($dql);
        $query->setParameter('source', $source);
        $titles = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return array(
            'source' => $source,
            'titles' => $titles,
        );
    }

    /**
     * Displays a form to edit an existing Source entity.
     *
     * @Route("/{id}/edit", name="source_edit", methods={"GET","POST"})
     * @Template()
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     *
     * @param Request $request
     * @param Source $source
     *
     * @return array|RedirectResponse
     */
    public function editAction(Request $request, Source $source) {
        $editForm = $this->createForm(SourceType::class, $source);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The source has been updated.');

            return $this->redirectToRoute('source_show', array('id' => $source->getId()));
        }

        return array(
            'source' => $source,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Source entity.
     *
     * @Route("/{id}/delete", name="source_delete", methods={"GET"})
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     *
     * @param Request $request
     * @param Source $source
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Source $source) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($source);
        $em->flush();
        $this->addFlash('success', 'The source was deleted.');

        return $this->redirectToRoute('source_index');
    }
}
