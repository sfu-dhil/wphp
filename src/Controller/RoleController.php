<?php

namespace App\Controller;

use App\Entity\Role;
use App\Form\RoleType;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Nines\UtilBundle\Controller\PaginatorTrait;

/**
 * Role controller.
 *
 * @Route("/role")
 */
class RoleController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Role entities.
     *
     * @Route("/", name="role_index", methods={"GET"})
     * @Template()
     *
     * @param Request $request
     * @param RoleRepository $repo
     *
     * @return array
     */
    public function indexAction(Request $request, RoleRepository $repo, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:Role e ORDER BY e.name';
        $query = $em->createQuery($dql);
        $roles = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return array(
            'roles' => $roles,
            'repo' => $repo,
        );
    }

    /**
     * @param Request $request
     * @param RoleRepository $repo
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     * @Route("/typeahead", name="role_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeaheadAction(Request $request, RoleRepository $repo) {
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
     * Creates a new Role entity.
     *
     * @Route("/new", name="role_new", methods={"GET","POST"})
     *
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($role);
            $em->flush();

            $this->addFlash('success', 'The new role was created.');

            return $this->redirectToRoute('role_show', array('id' => $role->getId()));
        }

        return array(
            'role' => $role,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Role entity.
     *
     * @Route("/{id}", name="role_show", methods={"GET"})
     * @Template()
     *
     * @param Request $request
     * @param Role $role
     *
     * @return array
     */
    public function showAction(Request $request, Role $role, EntityManagerInterface $em) {
        $dql = 'SELECT tr FROM App:TitleRole tr INNER JOIN tr.person p WHERE tr.role = :role ORDER BY p.lastName, p.firstName';
        $query = $em->createQuery($dql);
        $query->setParameter('role', $role);
        $titleRoles = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return array(
            'role' => $role,
            'titleRoles' => $titleRoles,
        );
    }

    /**
     * Displays a form to edit an existing Role entity.
     *
     * @Route("/{id}/edit", name="role_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     * @Template()
     *
     * @param Request $request
     * @param Role $role
     *
     * @return array|RedirectResponse
     */
    public function editAction(Request $request, Role $role, EntityManagerInterface $em) {
        $editForm = $this->createForm(RoleType::class, $role);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The role has been updated.');

            return $this->redirectToRoute('role_show', array('id' => $role->getId()));
        }

        return array(
            'role' => $role,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Role entity.
     *
     * @Route("/{id}/delete", name="role_delete", methods={"GET"})
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     *
     * @param Request $request
     * @param Role $role
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Role $role, EntityManagerInterface $em) {
        $em->remove($role);
        $em->flush();
        $this->addFlash('success', 'The role was deleted.');

        return $this->redirectToRoute('role_index');
    }
}
