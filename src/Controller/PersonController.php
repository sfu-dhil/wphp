<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Person;
use App\Entity\TitleRole;
use App\Form\Person\PersonSearchType;
use App\Form\Person\PersonType;
use App\Repository\PersonRepository;
use App\Services\CsvExporter;
use App\Services\JsonLdSerializer;
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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Person controller.
 */
#[Route(path: '/person')]
class PersonController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    #[Route(path: '/.{_format}', name: 'person_index', defaults: ['_format' => 'html'], methods: ['GET'])]
    public function indexAction(Request $request, JsonLdSerializer $jsonLdSerializer, EntityManagerInterface $em) : Response {
        $form = $this->createForm(PersonSearchType::class, null, [
            'action' => $this->generateUrl('person_search'),
            'entity_manager' => $em,
            'user' => $this->getUser(),
        ]);
        $dql = 'SELECT e FROM App:Person e';
        if (null === $this->getUser()) {
            $dql .= ' WHERE (e.finalcheck = 1)';
        }
        $query = $em->createQuery($dql);
        $people = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25, [
            'defaultSortFieldName' => ['e.lastName', 'e.firstName', 'e.dob'],
            'defaultSortDirection' => 'asc',
        ]);

        if (in_array($request->getRequestFormat(), ['jsonld', 'json'])) {
            $jsonLdItems = [];
            foreach ($people->getItems() as $person) {
                $jsonLdItems[] = $jsonLdSerializer->getPerson($person);
            }
            $requestParams = $request->query->all();
            $lastPage = (int) ceil((float) $people->getTotalItemCount() / (float) $people->getItemNumberPerPage());
            $currentPage = $people->getCurrentPageNumber();
            $jsonLd = [
                '@context' => 'https://www.w3.org/ns/hydra/core#',
                '@type' => 'Collection',
                '@id' => $this->generateUrl('person_index', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'totalItems' => $people->getTotalItemCount(),
                'member' => $jsonLdItems,
                'view' => [
                    '@id' => $this->generateUrl('person_index', array_merge($requestParams, ['page' => $currentPage]), UrlGeneratorInterface::ABSOLUTE_URL),
                    '@type' => 'PartialCollectionView',
                    'first' => $this->generateUrl('person_index', array_merge($requestParams, ['page' => 1]), UrlGeneratorInterface::ABSOLUTE_URL),
                    'previous' => 1 === $currentPage ? null : $this->generateUrl('person_index', array_merge($requestParams, ['page' => $currentPage - 1]), UrlGeneratorInterface::ABSOLUTE_URL),
                    'next' => $currentPage === $lastPage ? null : $this->generateUrl('person_index', array_merge($requestParams, ['page' => $currentPage + 1]), UrlGeneratorInterface::ABSOLUTE_URL),
                    'last' => $this->generateUrl('person_index', array_merge($requestParams, ['page' => $lastPage]), UrlGeneratorInterface::ABSOLUTE_URL),
                ],
            ];
            $response = new JsonResponse($jsonLd);
            $response->headers->set('Content-Type', 'application/ld+json');

            return $response;
        }
        if (in_array($request->getRequestFormat(), ['rdf', 'xml'])) {
            throw new AccessDeniedHttpException('RDF is not available on the index page.');
        }

        return $this->render('person/index.html.twig', [
            'search_form' => $form->createView(),
            'people' => $people,
            'sortable' => true,
        ]);
    }

    /**
     * Search for persons and return a JSON response for a typeahead widget.
     *
     * @return JsonResponse
     */
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    #[Route(path: '/typeahead', name: 'person_typeahead', methods: ['GET'])]
    public function typeaheadAction(Request $request, PersonRepository $repo) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];

        foreach ($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => $result->getFormId(),
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Full text search for Person entities.
     *
     * @return BinaryFileResponse
     */
    #[Route(path: '/search/export/{format}', name: 'person_search_export', methods: ['GET'], requirements: ['format' => '^csv$'])]
    #[Template]
    public function searchExportCsvAction(Request $request, EntityManagerInterface $em, PersonRepository $repo, CsvExporter $exporter) {
        $form = $this->createForm(PersonSearchType::class, null, ['entity_manager' => $em, 'user' => $this->getUser()]);
        $form->handleRequest($request);
        $query = $repo->buildSearchQuery($form->getData(), $this->getUser());
        $persons = $query->execute();

        $tmpPath = tempnam(sys_get_temp_dir(), 'wphp-export-');
        $fh = fopen($tmpPath, 'w');
        fputcsv($fh, $exporter->personHeaders());

        foreach ($persons as $person) {
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
     * @return array<string,mixed>     */
    #[Route(path: '/search', name: 'person_search', methods: ['GET'])]
    #[Template]
    public function searchAction(Request $request, PersonRepository $repo, EntityManagerInterface $em) {
        $form = $this->createForm(PersonSearchType::class, null, [
            'entity_manager' => $em,
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);
        $persons = [];
        $submitted = false;

        if ($form->isSubmitted()) {
            $submitted = true;
            $query = $repo->buildSearchQuery($form->getData(), $this->getUser());
            $persons = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        }

        return [
            'search_form' => $form->createView(),
            'people' => $persons,
            'submitted' => $submitted,
        ];
    }

    /**
     * Exports a person's titles in CSV.
     *
     * @return BinaryFileResponse
     */
    #[Route(path: '/{id}/export/{format}', name: 'person_export_csv', methods: ['GET', 'POST'], requirements: ['format' => '^csv$'])]
    #[Template]
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

        foreach ($titleRoles as $role) {
            fputcsv($fh, [...$exporter->personRow($person), $role->getRole()->getName(), ...$exporter->titleRow($role->getTitle())]);
        }

        fclose($fh);
        $response = new BinaryFileResponse($tmpPath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'wphp-person-' . $person->getId() . '-titles.csv');
        $response->deleteFileAfterSend(true);

        return $response;
    }

    /**
     * Exports a person's titles in a format.
     *
     * @return array<string,mixed>     */
    #[Route(path: '/{id}/export/{format}', name: 'person_export', methods: ['GET', 'POST'])]
    #[Template]
    public function exportAction(Request $request, Person $person, mixed $format) {
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
     * @return array<string,mixed>|RedirectResponse
     */
    #[Route(path: '/new', name: 'person_new', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    #[Template]
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
     * Exports all person entries in CSV.
     *
     * @return BinaryFileResponse
     */
    #[Route(path: '/export', name: 'person_export_all', methods: ['GET'])]
    public function exportAllAction(Request $request, CsvExporter $exporter, PersonRepository $repo) {
        $persons = [];
        if ($this->getUser()) {
            $persons = $repo->findAll();
        } else {
            $persons = $repo->findBy([
                'finalcheck' => 1,
            ]);
        }

        $tmpPath = tempnam(sys_get_temp_dir(), 'wphp-all-persons');
        $fh = fopen($tmpPath, 'w');
        fputcsv($fh, array_merge($exporter->personHeaders()));

        foreach ($persons as $person) {
            fputcsv($fh, $exporter->personRow($person));
        }
        fclose($fh);
        $response = new BinaryFileResponse($tmpPath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'wphp-all-persons.csv');
        $response->deleteFileAfterSend(true);

        return $response;
    }

    /**
     * Finds and displays a Person entity.
     *
     * @return array<string,mixed>     */
    #[Route(path: '/{id}.{_format}', name: 'person_show', defaults: ['_format' => 'html'], methods: ['GET'])]
    public function showAction(Request $request, JsonLdSerializer $jsonLdSerializer, Person $person) : Response {
        if (in_array($request->getRequestFormat(), ['rdf', 'xml'])) {
            $jsonLd = $jsonLdSerializer->getPerson($person);
            $response = new Response($jsonLdSerializer->toRDF($jsonLd));
            $response->headers->set('Content-Type', 'application/rdf+xml');
            return $response;
        } elseif (in_array($request->getRequestFormat(), ['jsonld', 'json'])) {
            $jsonLd = $jsonLdSerializer->getPerson($person);
            $response = new JsonResponse($jsonLd);
            $response->headers->set('Content-Type', 'application/ld+json');
            return $response;
        }
        $titleRoles = $person->getTitleRoles(true);
        if ( ! $this->getUser()) {
            $titleRoles = $titleRoles->filter(function (TitleRole $tr) {
                $title = $tr->getTitle();

                return $title->getFinalattempt() || $title->getFinalcheck();
            });
        }
        $pagination = $this->paginator->paginate($titleRoles, $request->query->getInt('page', 1), 25);

        return $this->render('person/show.html.twig', [
            'person' => $person,
            'pagination' => $pagination,
        ]);
    }

    /**
     * Displays a form to edit an existing Person entity.
     *
     * @return array<string,mixed>|RedirectResponse
     */
    #[Route(path: '/{id}/edit', name: 'person_edit', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    #[Template]
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
     * @return RedirectResponse
     */
    #[Route(path: '/{id}/delete', name: 'person_delete', methods: ['GET'])]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    public function deleteAction(Request $request, Person $person, EntityManagerInterface $em) {
        $em->remove($person);
        $em->flush();
        $this->addFlash('success', 'The person was deleted.');

        return $this->redirectToRoute('person_index');
    }
}
