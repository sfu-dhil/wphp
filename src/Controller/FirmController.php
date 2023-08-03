<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Firm;
use App\Entity\TitleFirmrole;
use App\Form\Firm\FirmSearchType;
use App\Form\Firm\FirmType;
use App\Repository\FirmRepository;
use App\Services\CsvExporter;
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
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Firm controller.
 */
#[Route(path: '/firm')]
class FirmController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Firm entities.
     *
     * @return array<string,mixed>     */
    #[Route(path: '/', name: 'firm_index', methods: ['GET'])]
    #[Template]
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $form = $this->createForm(FirmSearchType::class, null, [
            'action' => $this->generateUrl('firm_search'),
            'user' => $this->getUser(),
        ]);
        $dql = 'SELECT e FROM App:Firm e';
        if ('g.name+e.name' === $request->query->get('sort')) {
            $dql .= ' LEFT JOIN e.city g ORDER BY e.name, e.startDate';
        }
        if ( ! $this->getUser()) {
            $dql .= ' WHERE (e.finalcheck = 1)';
        }
        $query = $em->createQuery($dql);
        $firms = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25, [
            'defaultSortFieldName' => ['e.name', 'e.startDate'],
            'defaultSortDirection' => 'asc',
        ]);

        return [
            'search_form' => $form->createView(),
            'firms' => $firms,
            'sortable' => true,
        ];
    }

    /**
     * Search for firms and return a JSON repsonse for a typeahead widget.
     *
     * @return JsonResponse
     */
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    #[Route(path: '/typeahead', name: 'firm_typeahead', methods: ['GET'])]
    public function typeaheadAction(Request $request, FirmRepository $repo) {
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
     * Full text search for Firm entities.
     *
     * @return array<string,mixed>     */
    #[Route(path: '/search', name: 'firm_search', methods: ['GET'])]
    #[Template]
    public function searchAction(Request $request, FirmRepository $repo) {
        $form = $this->createForm(FirmSearchType::class, null, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);
        $firms = [];
        $submitted = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $submitted = true;
            $query = $repo->buildSearchQuery($form->getData(), $this->getUser());
            $firms = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        }

        return [
            'search_form' => $form->createView(),
            'firms' => $firms,
            'submitted' => $submitted,
        ];
    }

    /**
     * Full text search export for Title entities.
     *
     * @return BinaryFileResponse
     */
    #[Route(path: '/search/export', name: 'firm_search_export', methods: ['GET'])]
    public function searchExportAction(Request $request, FirmRepository $repo) {
        $form = $this->createForm(FirmSearchType::class, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);
        $firms = [];

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
            $firms = $query->execute();
        }

        $tmpPath = tempnam(sys_get_temp_dir(), 'wphp-export-');
        $fh = fopen($tmpPath, 'w');
        fputcsv($fh, ['ID', 'Name', 'Street Address', 'City', 'Start Date', 'End Date']);

        foreach ($firms as $firm) {
            fputcsv($fh, [
                $firm->getId(),
                $firm->getName(),
                $firm->getStreetAddress(),
                $firm->getCity() ? $firm->getCity()->getName() : '',
                preg_replace('/-00/', '', (string) $firm->getStartDate()),
                preg_replace('/-00/', '', (string) $firm->getEndDate()),
            ]);
        }
        fclose($fh);
        $response = new BinaryFileResponse($tmpPath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'wphp-firms-search' . $name . '.csv');
        $response->deleteFileAfterSend(true);

        return $response;
    }

    /**
     * Creates a new Firm entity.
     *
     * @return array<string,mixed>|RedirectResponse
     */
    #[Route(path: '/new', name: 'firm_new', methods: ['GET', 'POST'])]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    public function newAction(Request $request, EntityManagerInterface $em) {
        $firm = new Firm();
        $form = $this->createForm(FirmType::class, $firm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($firm);

            if ($firm->getFirmSources()->count() > 0) {
                foreach ($firm->getFirmSources() as $ts) {
                    $ts->setFirm($firm);
                    $em->persist($ts);
                }
            }

            $em->flush();
            $this->addFlash('success', 'The new firm was created.');

            return $this->redirectToRoute('firm_show', ['id' => $firm->getId()]);
        }

        return [
            'firm' => $firm,
            'form' => $form->createView(),
        ];
    }

    /**
     * Exports a firm's titles in a format.
     *
     * @return BinaryFileResponse
     */
    #[Route(path: '/export', name: 'firm_export_all', methods: ['GET'])]
    public function exportAllAction(Request $request, CsvExporter $exporter, FirmRepository $repo) {
        $firms = [];
        if ($this->getUser()) {
            $firms = $repo->findAll();
        } else {
            $firms = $repo->findBy([
                'finalcheck' => 1,
            ]);
        }

        $tmpPath = tempnam(sys_get_temp_dir(), 'wphp-all-firms-');
        $fh = fopen($tmpPath, 'w');
        fputcsv($fh, array_merge($exporter->firmHeaders()));

        foreach ($firms as $firm) {
            fputcsv($fh, $exporter->firmRow($firm));
        }
        fclose($fh);
        $response = new BinaryFileResponse($tmpPath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'wphp-all-firms.csv');
        $response->deleteFileAfterSend(true);

        return $response;
    }

    /**
     * Finds and displays a Firm entity.
     *
     * @return array<string,mixed>     */
    #[Route(path: '/{id}.{_format}', name: 'firm_show', defaults: ['_format' => 'html'], methods: ['GET'])]
    #[Template]
    public function showAction(Request $request, Firm $firm, SourceLinker $linker) {
        $firmRoles = $firm->getTitleFirmroles(true);
        if ( ! $this->getUser()) {
            $firmRoles = $firmRoles->filter(function (TitleFirmrole $tfr) {
                $title = $tfr->getTitle();

                return $title->getFinalattempt() || $title->getFinalcheck();
            });
        }
        $pagination = $this->paginator->paginate($firmRoles, $request->query->getInt('page', 1), 25);

        return [
            'firm' => $firm,
            'pagination' => $pagination,
            'linker' => $linker,
        ];
    }

    /**
     * Exports a firm's titles in a format.
     *
     * @return BinaryFileResponse
     */
    #[Route(path: '/{id}/export/{format}', name: 'firm_export_csv', methods: ['GET', 'POST'], requirements: ['format' => '^csv$'])]
    #[Template]
    public function exportCSVAction(Request $request, Firm $firm, CsvExporter $exporter) {
        $firmRoles = $firm->getTitleFirmroles(true);
        if ( ! $this->getUser()) {
            $firmRoles = $firmRoles->filter(function (TitleFirmrole $tfr) {
                $title = $tfr->getTitle();

                return $title->getFinalattempt() || $title->getFinalcheck();
            });
        }

        $tmpPath = tempnam(sys_get_temp_dir(), 'wphp-export-');
        $fh = fopen($tmpPath, 'w');
        fputcsv($fh, array_merge($exporter->firmHeaders(), ['Role'], $exporter->titleHeaders()));

        /** @var TitleFirmrole $role */
        foreach ($firmRoles as $role) {
            fputcsv($fh, [...$exporter->firmRow($role->getFirm()), $role->getFirmrole()
                ->getName(), ...$exporter->titleRow($role->getTitle())]);
        }
        fclose($fh);
        $response = new BinaryFileResponse($tmpPath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'wphp-firm-' . $firm->getId() . '-titles.csv');
        $response->deleteFileAfterSend(true);

        return $response;
    }

    /**
     * Exports a firm's titles in a format.
     *
     * @return array<string,mixed>     */
    #[Route(path: '/{id}/export/{format}', name: 'firm_export', methods: ['GET', 'POST'])]
    #[Template]
    public function exportAction(Request $request, Firm $firm, mixed $format) {
        $firmRoles = $firm->getTitleFirmroles(true);
        if ( ! $this->getUser()) {
            $firmRoles = $firmRoles->filter(function (TitleFirmrole $tfr) {
                $title = $tfr->getTitle();

                return $title->getFinalattempt() || $title->getFinalcheck();
            });
        }

        return [
            'firm' => $firm,
            'firmRoles' => $firmRoles,
            'format' => $format,
        ];
    }

    /**
     * Displays a form to edit an existing Firm entity.
     *
     * @return array<string,mixed>|RedirectResponse
     */
    #[Route(path: '/{id}/edit', name: 'firm_edit', methods: ['GET', 'POST'])]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    public function editAction(Request $request, Firm $firm, EntityManagerInterface $em) {
        $firmSources = new ArrayCollection();

        // Copy the firm sources
        foreach ($firm->getFirmSources() as $fs) {
            $firmSources->add($fs);
        }

        $editForm = $this->createForm(FirmType::class, $firm);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            // delete any firm sources that were removed in the form
            foreach ($firmSources as $fs) {
                if ( ! $firm->getFirmSources()->contains($fs)) {
                    $em->remove($fs);
                }
            }
            // Add any new firm sources added in the form
            if ($firm->getFirmSources()->count() > 0) {
                foreach ($firm->getFirmSources() as $ts) {
                    $ts->setFirm($firm);
                    $em->persist($ts);
                }
            }

            $em->flush();
            $this->addFlash('success', 'The firm has been updated.');

            return $this->redirectToRoute('firm_show', ['id' => $firm->getId()]);
        }

        return [
            'firm' => $firm,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Firm entity.
     *
     * @return RedirectResponse
     */
    #[Route(path: '/{id}/delete', name: 'firm_delete', methods: ['GET'])]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    public function deleteAction(Request $request, Firm $firm, EntityManagerInterface $em) {
        $em->remove($firm);
        $em->flush();
        $this->addFlash('success', 'The firm was deleted.');

        return $this->redirectToRoute('firm_index');
    }
}
