<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Title;
use App\Form\Title\TitleSearchType;
use App\Form\Title\TitleType;
use App\Repository\TitleRepository;
use App\Services\CsvExporter;
use App\Services\EstcMarcImporter;
use App\Services\JsonLdSerializer;
use App\Services\SourceLinker;
use Doctrine\Common\Collections\ArrayCollection;
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
 * Title controller.
 */
#[Route(path: '/title')]
class TitleController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Title entities.
     *
     * @return array<string,mixed>     */
    #[Route(path: '/.{_format}', name: 'title_index', defaults: ['_format' => 'html'], methods: ['GET'])]
    public function indexAction(Request $request, JsonLdSerializer $jsonLdSerializer, EntityManagerInterface $em) : Response {
        $dql = 'SELECT e FROM App:Title e';
        if (null === $this->getUser()) {
            $dql .= ' WHERE (e.finalcheck = 1 OR e.finalattempt = 1)';
        }
        $query = $em->createQuery($dql);

        $form = $this->createForm(TitleSearchType::class, null, [
            'action' => $this->generateUrl('title_search'),
            'user' => $this->getUser(),
        ]);
        $titles = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25, [
            'defaultSortFieldName' => ['e.title', 'e.pubdate'],
            'defaultSortDirection' => 'asc',
        ]);

        if ('json' === $request->getRequestFormat()) {
            $jsonLdItems = [];
            foreach ($titles->getItems() as $title) {
                $jsonLdItems[] = $jsonLdSerializer->getTitle($title);
            }
            $requestParams = $request->query->all();
            $lastPage = (int) ceil((float) $titles->getTotalItemCount() / (float) $titles->getItemNumberPerPage());
            $currentPage = $titles->getCurrentPageNumber();
            $jsonLd = [
                '@context' => 'https://www.w3.org/ns/hydra/core#',
                '@type' => 'Collection',
                '@id' => $this->generateUrl('title_index', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'totalItems' => $titles->getTotalItemCount(),
                'member' => $jsonLdItems,
                'view' => [
                    '@id' => $this->generateUrl('title_index', array_merge($requestParams, ['page' => $currentPage]), UrlGeneratorInterface::ABSOLUTE_URL),
                    '@type' => 'PartialCollectionView',
                    'first' => $this->generateUrl('title_index', array_merge($requestParams, ['page' => 1]), UrlGeneratorInterface::ABSOLUTE_URL),
                    'previous' => 1 === $currentPage ? null : $this->generateUrl('title_index', array_merge($requestParams, ['page' => $currentPage - 1]), UrlGeneratorInterface::ABSOLUTE_URL),
                    'next' => $currentPage === $lastPage ? null : $this->generateUrl('title_index', array_merge($requestParams, ['page' => $currentPage + 1]), UrlGeneratorInterface::ABSOLUTE_URL),
                    'last' => $this->generateUrl('title_index', array_merge($requestParams, ['page' => $lastPage]), UrlGeneratorInterface::ABSOLUTE_URL),
                ],
            ];
            $response = new JsonResponse($jsonLd);
            $response->headers->set('Content-Type', 'application/ld+json');

            return $response;
        }
        if ('xml' === $request->getRequestFormat()) {
            throw new AccessDeniedHttpException('RDF is not available on the index page.');
        }

        return $this->render('title/index.html.twig', [
            'search_form' => $form->createView(),
            'titles' => $titles,
            'sortable' => true,
        ]);
    }

    /**
     * Search for titles and return typeahead-widget-friendly JSON.
     *
     * @return JsonResponse
     */
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    #[Route(path: '/typeahead', name: 'title_typeahead', methods: ['GET'])]
    public function typeaheadAction(Request $request, TitleRepository $repo) {
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
     * Export a CSV with the titles.
     *
     * @return BinaryFileResponse
     */
    #[Route(path: '/export', name: 'title_export', methods: ['GET'])]
    public function exportAction(EntityManagerInterface $em, CsvExporter $exporter) {
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(Title::class, 'e');
        if (null === $this->getUser()) {
            $qb->where('e.finalcheck = 1 OR e.finalattempt = 1');
        }
        $qb->orderBy('e.id');
        $iterator = $qb->getQuery()->iterate();
        $tmpPath = tempnam(sys_get_temp_dir(), 'wphp-export-');
        $fh = fopen($tmpPath, 'w');
        fputcsv($fh, $exporter->titleHeaders());

        foreach ($iterator as $row) {
            fputcsv($fh, $exporter->titleRow($row[0]));
        }
        fclose($fh);
        $response = new BinaryFileResponse($tmpPath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'wphp-titles.csv');
        $response->deleteFileAfterSend(true);

        return $response;
    }

    /**
     * Full text search for Title entities.
     *
     * @return array<string,mixed>     */
    #[Route(path: '/search', name: 'title_search', methods: ['GET'])]
    #[Template]
    public function searchAction(Request $request, TitleRepository $repo) {
        $form = $this->createForm(TitleSearchType::class, null, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);
        $titles = [];
        $submitted = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $data = array_filter($form->getData());
            if (count((array) $data) > 2) {
                $submitted = true;
                $query = $repo->buildSearchQuery($data, $this->getUser());
                $titles = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
            }
        }

        return [
            'search_form' => $form->createView(),
            'titles' => $titles,
            'submitted' => $submitted,
        ];
    }

    /**
     * Full text search for Title entities.
     *
     * @return BinaryFileResponse
     */
    #[Route(path: '/search/export/{format}', name: 'title_search_export_csv', methods: ['GET'], requirements: ['format' => '^csv$'])]
    #[Template]
    public function searchExportCsvAction(Request $request, TitleRepository $repo, CsvExporter $exporter) {
        $form = $this->createForm(TitleSearchType::class, null, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);
        $titles = [];

        $name = '';
        if ($form->isSubmitted() && $form->isValid()) {
            $query = $repo->buildSearchQuery($form->getData(), $this->getUser());

            foreach ($query->getParameters() as $param) {
                $paramValue = $param->getValue();
                $value = '';
                if (is_array($paramValue)) {
                    $value = implode('-', array_map(fn ($e) => (string) $e, $paramValue));
                } else {
                    $value = $paramValue;
                }
                $name .= '-' . preg_replace('/[^a-zA-Z0-9-]*/', '', (string) $value);
            }
            $titles = $query->execute();
        }

        $tmpPath = tempnam(sys_get_temp_dir(), 'wphp-export-');
        $fh = fopen($tmpPath, 'w');
        fputcsv($fh, $exporter->titleHeaders());

        foreach ($titles as $title) {
            fputcsv($fh, $exporter->titleRow($title));
        }
        fclose($fh);
        $response = new BinaryFileResponse($tmpPath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'wphp-search-titles' . $name . '.csv');
        $response->deleteFileAfterSend(true);

        return $response;
    }

    /**
     * Full text search for Title entities.
     *
     * @return array<string,mixed>     */
    #[Route(path: '/search/export/{format}', name: 'title_search_export', methods: ['GET'])]
    #[Template]
    public function searchExportAction(Request $request, TitleRepository $repo, mixed $format) {
        $form = $this->createForm(TitleSearchType::class, null, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);
        $titles = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $query = $repo->buildSearchQuery($form->getData(), $this->getUser());
            $titles = $query->execute();
        }

        return [
            'titles' => $titles,
            'format' => $format,
        ];
    }

    /**
     * Creates a new Title entity.
     *
     * @return array<string,mixed>|RedirectResponse
     */
    #[Route(path: '/new', name: 'title_new', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    #[Template]
    public function newAction(Request $request, EntityManagerInterface $em) {
        $title = new Title();
        $form = $this->createForm(TitleType::class, $title);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // check for new titleFirmRoles and persist them.
            foreach ($title->getTitleFirmroles() as $tfr) {
                $tfr->setTitle($title);
                $em->persist($tfr);
            }

            // check for new titleFirmRoles and persist them.
            foreach ($title->getTitleroles() as $tr) {
                $tr->setTitle($title);
                $em->persist($tr);
            }

            foreach ($title->getTitleSources() as $ts) {
                $ts->setTitle($title);
                $em->persist($ts);
            }

            foreach ($title->getTitlesRelated() as $st) {
                $em->persist($st);
            }
            $em->persist($title);
            $em->flush();

            $this->addFlash('success', 'The new title was created.');

            return $this->redirectToRoute('title_show', ['id' => $title->getId()]);
        }

        return [
            'title' => $title,
            'form' => $form->createView(),
        ];
    }

    /**
     * Build a new title form prepopulated with data from a MARC record.
     *
     * @param string $id
     *
     * @return array<string,mixed> * @Route("/import/{id}", name="title_marc_import", methods={"GET"})
     */
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    #[Template('title/new.html.twig')]
    public function importMarcAction(Request $request, EstcMarcImporter $importer, $id) {
        $title = $importer->import($id);

        foreach ($importer->getMessages() as $message) {
            $this->addFlash('warning', $message);
        }
        $importer->resetMessages();

        $form = $this->createForm(TitleType::class, $title, [
            'action' => $this->generateUrl('title_new'),
        ]);

        return [
            'title' => $title,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Title entity.
     *
     * @return array<string,mixed>     */
    #[Route(path: '/{id}.{_format}', name: 'title_show', defaults: ['_format' => 'html'], methods: ['GET'])]
    public function showAction(Request $request, JsonLdSerializer $jsonLdSerializer, SourceLinker $linker, TitleRepository $repo, Title $title) : Response {
        if ( ! $this->getUser() && ! $title->getFinalattempt() && ! $title->getFinalcheck()) {
            throw new AccessDeniedHttpException('This title has not been verified and is not available to the public.');
        }
        if (in_array($request->getRequestFormat(), ['xml', 'json'], true)) {
            $jsonLd = $jsonLdSerializer->getTitle($title);
            if ('xml' === $request->getRequestFormat()) {
                $response = new Response($jsonLdSerializer->toRDF($jsonLd));
                $response->headers->set('Content-Type', 'application/rdf+xml');

                return $response;
            }
            $response = new JsonResponse($jsonLd);
            $response->headers->set('Content-Type', 'application/ld+json');

            return $response;

        }
        $similar = [];
        if ($this->getUser()) {
            $similar = $repo->moreLike($title);
        }

        return $this->render('title/show.html.twig', [
            'title' => $title,
            'linker' => $linker,
            'similar' => $similar,
        ]);
    }

    /**
     * Displays a form to edit an existing Title entity.
     *
     * @return array<string,mixed>|RedirectResponse
     */
    #[Route(path: '/{id}/edit', name: 'title_edit', methods: ['GET', 'POST'])]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    public function editAction(Request $request, Title $title, EntityManagerInterface $em) {
        // collect the titleFirmRole objects before modification.
        $titleFirmRoles = new ArrayCollection();

        foreach ($title->getTitleFirmroles() as $tfr) {
            $titleFirmRoles->add($tfr);
        }

        $titleRoles = new ArrayCollection();

        foreach ($title->getTitleroles() as $tr) {
            $titleRoles->add($tr);
        }

        $titleSources = new ArrayCollection();

        foreach ($title->getTitleSources() as $ts) {
            $titleSources->add($ts);
        }

        $editForm = $this->createForm(TitleType::class, $title);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            // check for deleted titleFirmRoles and remove them.
            foreach ($titleFirmRoles as $tfr) {
                if ( ! $title->getTitleFirmroles()->contains($tfr)) {
                    $em->remove($tfr);
                }
            }

            // check for deleted titleRoles and remove them.
            foreach ($titleRoles as $tr) {
                if ( ! $title->getTitleroles()->contains($tr)) {
                    $em->remove($tr);
                }
            }

            foreach ($titleSources as $ts) {
                if ( ! $title->getTitleSources()->contains($ts)) {
                    $em->remove($ts);
                }
            }

            // check for new titleFirmRoles and persist them.
            foreach ($title->getTitleroles() as $tr) {
                if ( ! $titleRoles->contains($tr)) {
                    $tr->setTitle($title);
                    $em->persist($tr);
                }
            }

            // check for new titleFirmRoles and persist them.
            foreach ($title->getTitleFirmroles() as $tfr) {
                if ( ! $titleFirmRoles->contains($tfr)) {
                    $tfr->setTitle($title);
                    $em->persist($tfr);
                }
            }

            foreach ($title->getTitleSources() as $ts) {
                if ( ! $titleSources->contains($ts)) {
                    $ts->setTitle($title);
                    $em->persist($ts);
                }
            }

            $em->flush();
            $this->addFlash('success', 'The title has been updated.');

            return $this->redirectToRoute('title_show', ['id' => $title->getId()]);
        }

        return [
            'title' => $title,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Title entity.
     *
     * @return array<string,mixed>     */
    #[Route(path: '/{id}/copy', name: 'title_copy', methods: ['GET', 'POST'])]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    public function copyAction(Request $request, Title $title, EntityManagerInterface $em) {
        $form = $this->createForm(TitleType::class, $title, [
            'action' => $this->generateUrl('title_new'),
        ]);

        return [
            'title' => $title,
            'form' => $form->createView(),
        ];
    }

    /**
     * Deletes a Title entity.
     *
     * @return RedirectResponse
     */
    #[Route(path: '/{id}/delete', name: 'title_delete', methods: ['GET'])]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    public function deleteAction(Request $request, Title $title, EntityManagerInterface $em) {
        foreach ($title->getTitleFirmroles() as $tfr) {
            $em->remove($tfr);
        }

        foreach ($title->getTitleRoles() as $tr) {
            $em->remove($tr);
        }

        foreach ($title->getTitleSources() as $ts) {
            $em->remove($ts);
        }
        $em->remove($title);
        $em->flush();
        $this->addFlash('success', 'The title was deleted.');

        return $this->redirectToRoute('title_index');
    }
}
