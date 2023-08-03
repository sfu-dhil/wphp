<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Format;
use App\Form\FormatType;
use App\Repository\FormatRepository;
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
 * Format controller.
 */
#[Route(path: '/format')]
class FormatController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Format entities.
     *
     * @return array<string,mixed>     */
    #[Route(path: '/', name: 'format_index', methods: ['GET'])]
    #[Template]
    public function indexAction(Request $request, FormatRepository $repo, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:Format e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $formats = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'formats' => $formats,
            'repo' => $repo,
        ];
    }

    /**
     * Searchf for formats and return a JSON response for a typeahead widget.
     *
     * @return JsonResponse
     */
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    #[Route(path: '/typeahead', name: 'format_typeahead', methods: ['GET'])]
    public function typeaheadAction(Request $request, FormatRepository $repo) {
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
     * Creates a new Format entity.
     *
     * @return array<string,mixed>|RedirectResponse
     */
    #[Route(path: '/new', name: 'format_new', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    #[Template]
    public function newAction(Request $request, EntityManagerInterface $em) {
        $format = new Format();
        $form = $this->createForm(FormatType::class, $format);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($format);
            $em->flush();

            $this->addFlash('success', 'The new format was created.');

            return $this->redirectToRoute('format_show', ['id' => $format->getId()]);
        }

        return [
            'format' => $format,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Format entity.
     *
     * @return array<string,mixed>     */
    #[Route(path: '/{id}', name: 'format_show', methods: ['GET'])]
    #[Template]
    public function showAction(Request $request, Format $format, EntityManagerInterface $em) {
        $dql = 'SELECT t FROM App:Title t WHERE t.format = :format';
        if (null === $this->getUser()) {
            $dql .= ' AND (t.finalcheck = 1 OR t.finalattempt = 1)';
        }
        $dql .= ' ORDER BY t.title';

        $query = $em->createQuery($dql);
        $query->setParameter('format', $format);
        $titles = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'format' => $format,
            'titles' => $titles,
        ];
    }

    /**
     * Displays a form to edit an existing Format entity.
     *
     * @return array<string,mixed>|RedirectResponse
     */
    #[Route(path: '/{id}/edit', name: 'format_edit', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    #[Template]
    public function editAction(Request $request, Format $format, EntityManagerInterface $em) {
        $editForm = $this->createForm(FormatType::class, $format);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The format has been updated.');

            return $this->redirectToRoute('format_show', ['id' => $format->getId()]);
        }

        return [
            'format' => $format,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Format entity.
     *
     * @return RedirectResponse
     */
    #[Route(path: '/{id}/delete', name: 'format_delete', methods: ['GET'])]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    public function deleteAction(Request $request, Format $format, EntityManagerInterface $em) {
        $em->remove($format);
        $em->flush();
        $this->addFlash('success', 'The format was deleted.');

        return $this->redirectToRoute('format_index');
    }
}
