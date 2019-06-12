<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Firmrole;
use AppBundle\Form\FirmroleType;
use AppBundle\Repository\FirmroleRepository;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Firmrole controller.
 *
 * @Route("/firmrole")
 */
class FirmroleController extends Controller  implements PaginatorAwareInterface {

    use PaginatorTrait;

    /**
     * Lists all Firmrole entities.
     *
     * @Route("/", name="firmrole_index", methods={"GET"})

     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request, FirmroleRepository $repo) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Firmrole e ORDER BY e.name';
        $query = $em->createQuery($dql);
        $firmroles = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25, array(
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        ));

        return array(
            'firmroles' => $firmroles,
            'repo' => $repo,
        );
    }

    /**
     * @param Request $request
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Route("/typeahead", name="firmrole_typeahead", methods={"GET"})

     * @return JsonResponse
     */
    public function typeaheadAction(Request $request, FirmroleRepository $repo) {
        $q = $request->query->get('q');
        if( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];
        foreach($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => $result->getName(),
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Creates a new Firmrole entity.
     *
     * @Route("/new", name="firmrole_new", methods={"GET", "POST"})

     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        $firmrole = new Firmrole();
        $form = $this->createForm(FirmroleType::class, $firmrole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($firmrole);
            $em->flush();

            $this->addFlash('success', 'The new firmrole was created.');
            return $this->redirectToRoute('firmrole_show', array('id' => $firmrole->getId()));
        }

        return array(
            'firmrole' => $firmrole,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Firmrole entity.
     *
     * @Route("/{id}", name="firmrole_show", methods={"GET"})

     * @Template()
     * @param Firmrole $firmrole
     */
    public function showAction(Request $request, Firmrole $firmrole) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT tfr FROM AppBundle:TitleFirmrole tfr WHERE tfr.firmrole = :firmrole';
        $query = $em->createQuery($dql);
        $query->setParameter('firmrole', $firmrole);
        $titleFirmRoles = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return array(
            'firmrole' => $firmrole,
            'titleFirmRoles' => $titleFirmRoles,
        );
    }

    /**
     * Displays a form to edit an existing Firmrole entity.
     *
     * @Route("/{id}/edit", name="firmrole_edit", methods={"GET", "POST"})

     * @Template()
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @param Request $request
     * @param Firmrole $firmrole
     */
    public function editAction(Request $request, Firmrole $firmrole) {
        $editForm = $this->createForm(FirmroleType::class, $firmrole);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The firmrole has been updated.');
            return $this->redirectToRoute('firmrole_show', array('id' => $firmrole->getId()));
        }

        return array(
            'firmrole' => $firmrole,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Firmrole entity.
     *
     * @Route("/{id}/delete", name="firmrole_delete", methods={"GET"})

     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @param Request $request
     * @param Firmrole $firmrole
     */
    public function deleteAction(Request $request, Firmrole $firmrole) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($firmrole);
        $em->flush();
        $this->addFlash('success', 'The firmrole was deleted.');

        return $this->redirectToRoute('firmrole_index');
    }

}
