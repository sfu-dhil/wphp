<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Format;
use AppBundle\Form\FormatType;
use AppBundle\Repository\FormatRepository;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Format controller.
 *
 * @Route("/format")
 */
class FormatController extends Controller  implements PaginatorAwareInterface {

    use PaginatorTrait;

    /**
     * Lists all Format entities.
     *
     * @Route("/", name="format_index", methods={"GET"})
     * @Template()
     * @param Request $request
     *
     * @param FormatRepository $repo
     *
     * @return array
     */
    public function indexAction(Request $request, FormatRepository $repo) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Format e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $formats = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return array(
            'formats' => $formats,
            'repo' => $repo,
        );
    }

    /**
     * Searchf for formats and return a JSON response for a typeahead widget.
     *
     * @param Request $request
     * @param FormatRepository $repo
     *
     * @return JsonResponse
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Route("/typeahead", name="format_typeahead", methods={"GET"})
     */
    public function typeaheadAction(Request $request, FormatRepository $repo) {
        $q = $request->query->get('q');
        if (!$q) {
            return new JsonResponse([]);
        }
        $data = [];
        foreach ($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => $result->getName(),
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Creates a new Format entity.
     *
     * @Route("/new", name="format_new", methods={"GET", "POST"})
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Template()
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAction(Request $request) {
        $format = new Format();
        $form = $this->createForm(FormatType::class, $format);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($format);
            $em->flush();

            $this->addFlash('success', 'The new format was created.');
            return $this->redirectToRoute('format_show', array('id' => $format->getId()));
        }

        return array(
            'format' => $format,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Format entity.
     *
     * @Route("/{id}", name="format_show", methods={"GET"})
     * @Template()
     * @param Request $request
     * @param Format $format
     *
     * @return array
     */
    public function showAction(Request $request, Format $format) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT t FROM AppBundle:Title t WHERE t.format = :format ORDER BY t.title';
        $query = $em->createQuery($dql);
        $query->setParameter('format', $format);
        $titles = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return array(
            'format' => $format,
            'titles' => $titles,
        );
    }

    /**
     * Displays a form to edit an existing Format entity.
     *
     * @Route("/{id}/edit", name="format_edit", methods={"GET","POST"})
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Template()
     * @param Request $request
     * @param Format $format
     *
     * @return array|RedirectResponse
     */
    public function editAction(Request $request, Format $format) {
        $editForm = $this->createForm(FormatType::class, $format);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The format has been updated.');
            return $this->redirectToRoute('format_show', array('id' => $format->getId()));
        }

        return array(
            'format' => $format,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Format entity.
     *
     * @Route("/{id}/delete", name="format_delete", methods={"GET"})
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @param Request $request
     * @param Format $format
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Format $format) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($format);
        $em->flush();
        $this->addFlash('success', 'The format was deleted.');

        return $this->redirectToRoute('format_index');
    }

}
