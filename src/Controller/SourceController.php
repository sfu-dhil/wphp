<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Source;
use App\Entity\TitleSource;
use App\Entity\FirmSource;
use App\Form\SourceType;
use App\Repository\SourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Source controller.
 */
#[Route(path: '/source')]
class SourceController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Source entities.
     *
     * @return array<string,mixed>     */
    #[Route(path: '/', name: 'source_index', methods: ['GET'])]
    #[Template]
    public function indexAction(Request $request, EntityManagerInterface $em, SourceRepository $repo) {
        $titleQb = $em->createQueryBuilder();
        $titleQb->select('IDENTITY(ts.source) as srcId, COUNT(DISTINCT(ts.title)) as cnt');
        $titleQb->from(TitleSource::class, 'ts');
        $titleQb->groupBy('ts.source');
        $titleQb->orderBy('ts.source');
        $titleCounts = [];
        foreach ($titleQb->getQuery()->getResult() as $result) {
            $titleCounts[$result['srcId']] = $result['cnt'];
        }
        $firmQb = $em->createQueryBuilder();
        $firmQb->select('IDENTITY(fs.source) as srcId, COUNT(DISTINCT(fs.firm)) as cnt');
        $firmQb->from(FirmSource::class, 'fs');
        $firmQb->groupBy('fs.source');
        $firmQb->orderBy('fs.source');
        $firmCounts = [];
        foreach ($firmQb->getQuery()->getResult() as $result) {
            $firmCounts[$result['srcId']] = $result['cnt'];
        }

        $dql = 'SELECT e FROM App:Source e ORDER BY e.name';
        $query = $em->createQuery($dql);
        $sources = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'sources' => $sources,
            'repo' => $repo,
            'titleCounts' => $titleCounts,
            'firmCounts' => $firmCounts,
        ];
    }

    /**
     * Typeahead action for editor widgets.
     *
     * @return JsonResponse
     */
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    #[Route(path: '/typeahead', name: 'source_typeahead', methods: ['GET'])]
    public function typeaheadAction(Request $request, SourceRepository $repo) {
        $q = $request->query->get('q');
        if ( ! $q) {
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
     * Creates a new Source entity.
     *
     * @return array<string,mixed>|RedirectResponse
     */
    #[Route(path: '/new', name: 'source_new', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    #[Template]
    public function newAction(Request $request, EntityManagerInterface $em) {
        $source = new Source();
        $form = $this->createForm(SourceType::class, $source);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($source);
            $em->flush();

            $this->addFlash('success', 'The new source was created.');

            return $this->redirectToRoute('source_show', ['id' => $source->getId()]);
        }

        return [
            'source' => $source,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Source entity.
     *
     * @return array<string,mixed>     */
    #[Route(path: '/{id}', name: 'source_show', methods: ['GET'])]
    #[Template]
    public function showAction(Request $request, Source $source, EntityManagerInterface $em) {
        $query = $em->createQuery('SELECT t FROM App:Title t INNER JOIN t.titleSources ts WHERE ts.source = :source ORDER BY t.title');
        $query->setParameter('source', $source);
        $titles = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        $titleCountQuery = $em->createQuery('SELECT COUNT(DISTINCT(t.id)) FROM App:Title t INNER JOIN t.titleSources ts WHERE ts.source = :source');
        $titleCountQuery->setParameter('source', $source);
        $firmCountQuery = $em->createQuery('SELECT COUNT(DISTINCT(f.id)) FROM App:Firm f INNER JOIN f.firmSources fs WHERE fs.source = :source');
        $firmCountQuery->setParameter('source', $source);
        return [
            'source' => $source,
            'titles' => $titles,
            'titleCount' => $titleCountQuery->getSingleScalarResult(),
            'firmCount' => $firmCountQuery->getSingleScalarResult(),
        ];
    }

    #[Route(path: '/{id}/firms', name: 'source_firm_show', methods: ['GET'])]
    #[Template]
    public function firmsAction(Request $request, Source $source, EntityManagerInterface $em) {
        $query = $em->createQuery('SELECT f FROM App:Firm f INNER JOIN f.firmSources fs WHERE fs.source = :source ORDER BY f.name');
        $query->setParameter('source', $source);
        $firms = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        $titleCountQuery = $em->createQuery('SELECT COUNT(DISTINCT(t.id)) FROM App:Title t INNER JOIN t.titleSources ts WHERE ts.source = :source');
        $titleCountQuery->setParameter('source', $source);
        $firmCountQuery = $em->createQuery('SELECT COUNT(DISTINCT(f.id)) FROM App:Firm f INNER JOIN f.firmSources fs WHERE fs.source = :source');
        $firmCountQuery->setParameter('source', $source);
        return [
            'source' => $source,
            'firms' => $firms,
            'titleCount' => $titleCountQuery->getSingleScalarResult(),
            'firmCount' => $firmCountQuery->getSingleScalarResult(),
        ];
    }

    /**
     * Displays a form to edit an existing Source entity.
     *
     * @return array<string,mixed>|RedirectResponse
     */
    #[Route(path: '/{id}/edit', name: 'source_edit', methods: ['GET', 'POST'])]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    public function editAction(Request $request, Source $source, EntityManagerInterface $em) {
        $editForm = $this->createForm(SourceType::class, $source);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The source has been updated.');

            return $this->redirectToRoute('source_show', ['id' => $source->getId()]);
        }

        return [
            'source' => $source,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Source entity.
     *
     * @return RedirectResponse
     */
    #[Route(path: '/{id}/delete', name: 'source_delete', methods: ['GET'])]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    public function deleteAction(Request $request, Source $source, EntityManagerInterface $em) {
        $em->remove($source);
        $em->flush();
        $this->addFlash('success', 'The source was deleted.');

        return $this->redirectToRoute('source_index');
    }
}
