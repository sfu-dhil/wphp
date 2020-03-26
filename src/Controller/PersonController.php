<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Person;
use App\Entity\TitleRole;
use App\Form\Person\PersonSearchType;
use App\Form\Person\PersonType;
use App\Repository\PersonRepository;
use App\Services\CsvExporter;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Person controller.
 *
 * @Route("/person")
 */
class PersonController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Person entities.
     *
     * @Route("/", name="person_index", methods={"GET"})
     *
     * @Template()
     *
     * @return array
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $form = $this->createForm(PersonSearchType::class, null, [
            'action' => $this->generateUrl('person_search'),
            'entity_manager' => $em,
        ]);
        $dql = 'SELECT e FROM App:Person e';
        $query = $em->createQuery($dql);
        $people = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25, [
            'defaultSortFieldName' => ['e.lastName', 'e.firstName', 'e.dob'],
            'defaultSortDirection' => 'asc',
        ]);

        return [
            'search_form' => $form->createView(),
            'people' => $people,
            'sortable' => true,
        ];
    }

    /**
     * Search for persons and return a JSON response for a typeahead widget.
     *
     * @return JsonResponse
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     * @Route("/typeahead", name="person_typeahead", methods={"GET"})
     */
    public function typeaheadAction(Request $request, PersonRepository $repo) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
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
     * @Route("/search/export/{format}", name="person_search_export", methods={"GET"}, requirements={"format"="^csv$"})
     * @Template()
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param PersonRepository $repo
     * @param CsvExporter $exporter
     *
     * @return BinaryFileResponse
     */
    public function searchExportCsvAction(Request $request, EntityManagerInterface $em, PersonRepository $repo, CsvExporter $exporter) {
        $form = $this->createForm(PersonSearchType::class, null, ['entity_manager' => $em]);
        $form->handleRequest($request);
        $persons = [];

        if ($form->isSubmitted()) {
            $query = $repo->buildSearchQuery($form->getData());
            $persons = $query->execute();
        }
        $tmpPath = tempnam(sys_get_temp_dir(), 'wphp-export-');
        $fh = fopen($tmpPath, 'w');
        fputcsv($fh, $exporter->personHeaders());
        foreach($persons as $person) {
            fputcsv($fh, $exporter->personRow($person));
        }

        fclose($fh);
        $response = new BinaryFileResponse($tmpPath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'wphp-search-persons.csv');
        $response->deleteFileAfterSend(true);

        return $response;
    }

    /**
     * Full text search for Person entities.
     *
     * @Route("/search", name="person_search", methods={"GET"})
     * @Template()
     *
     * @return array
     */
    public function searchAction(Request $request, PersonRepository $repo, EntityManagerInterface $em) {
        $form = $this->createForm(PersonSearchType::class, null, ['entity_manager' => $em]);
        $form->handleRequest($request);
        $persons = [];
        $submitted = false;

        if ($form->isSubmitted()) {
            $submitted = true;
            $query = $repo->buildSearchQuery($form->getData());
            $persons = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        }

        return [
            'search_form' => $form->createView(),
            'people' => $persons,
            'submitted' => $submitted,
        ];
    }

    /**
     * Search for Title entities.
     *
     * @Route("/jump", name="person_jump", methods={"GET"})
     *
     * @Template()
     *
     * @return array
     */
    public function jumpAction(Request $request) {
        $q = $request->query->getInt('q');
        if ($q) {
            return $this->redirect($this->generateUrl('person_show', ['id' => $q]));
        }

        return $this->redirect($this->generateUrl('person_index', ['id' => $q]));
    }

    /**
     * Exports a person's titles in CSV.
     *
     * @Route("/{id}/export/{format}", name="person_export_csv", methods={"GET","POST"}, requirements={"format" = "^csv$"})
     * @Template()
     *
     * @return BinaryFileResponse
     */
    public function exportCsvAction(Request $request, Person $person, CsvExporter $exporter) {
        $titleRoles = $person->getTitleRoles(true);
        if ( ! $this->getUser()) {
            $titleRoles = $titleRoles->filter(function (TitleRole $tr) {
                $title = $tr->getTitle();

                return $title->getFinalattempt() || $title->getFinalcheck();
            });
        }

        $tmpPath = tempnam(sys_get_temp_dir(), 'wphp-export-');
        $fh = fopen($tmpPath, 'w');
        fputcsv($fh, array_merge($exporter->personHeaders(), ['Role'], $exporter->titleHeaders()));
        foreach($titleRoles as $role) {
            fputcsv($fh, array_merge($exporter->personRow($person), [$role->getRole()->getName()], $exporter->titleRow($role->getTitle())));
        }

        fclose($fh);
        $response = new BinaryFileResponse($tmpPath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'wphp-person-titles.csv');
        $response->deleteFileAfterSend(true);

        return $response;
    }

    /**
     * Exports a person's titles in a format.
     *
     * @Route("/{id}/export/{format}", name="person_export", methods={"GET","POST"})
     * @Template()
     *
     * @return array
     */
    public function exportAction(Request $request, Person $person, $format) {
        $titleRoles = $person->getTitleRoles(true);
        if ( ! $this->getUser()) {
            $titleRoles = $titleRoles->filter(function (TitleRole $tr) {
                $title = $tr->getTitle();

                return $title->getFinalattempt() || $title->getFinalcheck();
            });
        }

        return [
            'person' => $person,
            'titles' => $titleRoles,
            'format' => $format,
        ];
    }

    /**
     * Creates a new Person entity.
     *
     * @Route("/new", name="person_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     * @Template()
     *
     * @return array|RedirectResponse
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($person);
            $em->flush();

            $this->addFlash('success', 'The new person was created.');

            return $this->redirectToRoute('person_show', ['id' => $person->getId()]);
        }

        return [
            'person' => $person,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Person entity.
     *
     * @Route("/{id}.{_format}", name="person_show", defaults={"_format": "html"}, methods={"GET"})
     * @Template()
     *
     * @return array
     */
    public function showAction(Request $request, Person $person) {
        $titleRoles = $person->getTitleRoles(true);
        if ( ! $this->getUser()) {
            $titleRoles = $titleRoles->filter(function (TitleRole $tr) {
                $title = $tr->getTitle();

                return $title->getFinalattempt() || $title->getFinalcheck();
            });
        }

        $pagination = $this->paginator->paginate($titleRoles, $request->query->getInt('page', 1), 25);

        return [
            'person' => $person,
            'pagination' => $pagination,
        ];
    }

    /**
     * Displays a form to edit an existing Person entity.
     *
     * @Route("/{id}/edit", name="person_edit", methods={"GET","POST"})
     *
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     * @Template()
     *
     * @return array
     */
    public function editAction(Request $request, Person $person, EntityManagerInterface $em) {
        $editForm = $this->createForm(PersonType::class, $person);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The person has been updated.');

            return $this->redirectToRoute('person_show', ['id' => $person->getId()]);
        }

        return [
            'person' => $person,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Person entity.
     *
     * @Route("/{id}/delete", name="person_delete", methods={"GET"})
     *
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Person $person, EntityManagerInterface $em) {
        $em->remove($person);
        $em->flush();
        $this->addFlash('success', 'The person was deleted.');

        return $this->redirectToRoute('person_index');
    }
}
