<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Person;
use AppBundle\Form\Person\PersonSearchType;
use AppBundle\Form\Person\PersonType;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Person controller.
 *
 * @Route("/person")
 */
class PersonController extends Controller implements PaginatorAwareInterface {

    use PaginatorTrait;

    /**
     * Lists all Person entities.
     *
     * @Route("/", name="person_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(PersonSearchType::class, null, array(
            'action' => $this->generateUrl('person_search'),
            'entity_manager' => $em
        ));
        $dql = 'SELECT e FROM AppBundle:Person e';
        $query = $em->createQuery($dql);
        $people = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25, array(
            'defaultSortFieldName' => ['e.lastName', 'e.firstName', 'e.dob'],
            'defaultSortDirection' => 'asc',
        ));
        return array(
            'search_form' => $form->createView(),
            'people' => $people,
        );
    }

    /**
     * @param Request $request
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Route("/typeahead", name="person_typeahead")
     * @Method("GET")
     * @return JsonResponse
     */
    public function typeaheadAction(Request $request) {
        $q = $request->query->get('q');
        if (!$q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Person::class);
        $data = [];
        foreach ($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => $result->getLastname() . ', ' . $result->getFirstname(),
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Full text search for Person entities.
     *
     * @Route("/search", name="person_search")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(PersonSearchType::class, null, array('entity_manager' => $em));
        $form->handleRequest($request);
        $persons = array();
        $submitted = false;

        if ($form->isSubmitted()) {
            $submitted = true;
            $repo = $em->getRepository(Person::class);
            $query = $repo->buildSearchQuery($form->getData());
            $persons = $this->paginator->paginate($query->execute(), $request->query->getInt('page', 1), 25);
        }
        return array(
            'search_form' => $form->createView(),
            'people' => $persons,
            'submitted' => $submitted,
        );
    }

    /**
     * Search for Title entities.
     *
     * @Route("/jump", name="person_jump")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function jumpAction(Request $request) {
        $q = $request->query->getInt('q');
        if ($q) {
            return $this->redirect($this->generateUrl('person_show', array('id' => $q)));
        } else {
            return $this->redirect($this->generateUrl('person_index', array('id' => $q)));
        }
    }

    /**
     * Exports a person's titles in a format.
     *
     * @Route("/{id}/export", name="person_export")
     * @Method({"GET","POST"})
     * @Template()
     * @param Request $request
     * @param Person $person
     * @return array
     */
    public function exportAction(Request $request, Person $person) {
        $titles = $person->getTitleRoles();
        return array(
            'person' => $person,
            'titles' => $titles,
            'format' => $request->query->get('format', 'mla'),
        );
    }

    /**
     * Creates a new Person entity.
     *
     * @Route("/new", name="person_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            $this->addFlash('success', 'The new person was created.');
            return $this->redirectToRoute('person_show', array('id' => $person->getId()));
        }

        return array(
            'person' => $person,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Person entity.
     *
     * @Route("/{id}.{_format}", name="person_show", defaults={"_format": "html"})
     * @Method({"GET","POST"})
     * @Template()
     * @param Person $person
     * @return array
     */
    public function showAction(Request $request, Person $person) {
        $titleRoles = $person->getTitleRoles();
        $pagination = $this->paginator->paginate($titleRoles, $request->query->getInt('page', 1), 25);

        return array(
            'person' => $person,
            'pagination' => $pagination,
        );
    }

    /**
     * Displays a form to edit an existing Person entity.
     *
     * @Route("/{id}/edit", name="person_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Template()
     * @param Request $request
     * @param Person $person
     */
    public function editAction(Request $request, Person $person) {
        $editForm = $this->createForm(PersonType::class, $person);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The person has been updated.');
            return $this->redirectToRoute('person_show', array('id' => $person->getId()));
        }

        return array(
            'person' => $person,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Person entity.
     *
     * @Route("/{id}/delete", name="person_delete")
     * @Method("GET")
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @param Request $request
     * @param Person $person
     */
    public function deleteAction(Request $request, Person $person) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($person);
        $em->flush();
        $this->addFlash('success', 'The person was deleted.');

        return $this->redirectToRoute('person_index');
    }

}
