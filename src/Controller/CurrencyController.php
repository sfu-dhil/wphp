<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Currency;
use App\Form\CurrencyType;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/currency')]
class CurrencyController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @return array<string,mixed>
     */
    #[Route(path: '/', name: 'currency_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, CurrencyRepository $currencyRepository) : array {
        $query = $currencyRepository->indexQuery();
        $pageSize = $this->getParameter('page_size');
        $page = $request->query->getint('page', 1);

        return [
            'currencies' => $this->paginator->paginate($query, $page, $pageSize),
        ];
    }

    /**
     * @return array<string,mixed>
     */
    #[Route(path: '/search', name: 'currency_search', methods: ['GET'])]
    #[Template]
    public function search(Request $request, CurrencyRepository $currencyRepository) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $currencyRepository->searchQuery($q);
            $currencies = $this->paginator->paginate($query, $request->query->getInt('page', 1), $this->getParameter('page_size'), ['wrap-queries' => true]);
        } else {
            $currencies = [];
        }

        return [
            'currencies' => $currencies,
            'q' => $q,
        ];
    }

    /**
     * @return JsonResponse
     */
    #[Route(path: '/typeahead', name: 'currency_typeahead', methods: ['GET'])]
    public function typeahead(Request $request, CurrencyRepository $currencyRepository) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];

        foreach ($currencyRepository->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string) $result,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @return array<string,mixed>|RedirectResponse
     */
    #[Route(path: '/new', name: 'currency_new', methods: ['GET', 'POST'])]
    #[Template]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function new(EntityManagerInterface $entityManager, Request $request) {
        $currency = new Currency();
        $form = $this->createForm(CurrencyType::class, $currency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($currency);
            $entityManager->flush();
            $this->addFlash('success', 'The new currency has been saved.');

            return $this->redirectToRoute('currency_show', ['id' => $currency->getId()]);
        }

        return [
            'currency' => $currency,
            'form' => $form->createView(),
        ];
    }

    /**
     * @return array<string,mixed>
     */
    #[Route(path: '/{id}', name: 'currency_show', methods: ['GET'])]
    #[Template]
    public function show(Currency $currency) {
        return [
            'currency' => $currency,
        ];
    }

    /**
     * @return array<string,mixed>|RedirectResponse
     */
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Route(path: '/{id}/edit', name: 'currency_edit', methods: ['GET', 'POST'])]
    #[Template]
    public function edit(EntityManagerInterface $entityManager, Request $request, Currency $currency) {
        $form = $this->createForm(CurrencyType::class, $currency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'The updated currency has been saved.');

            return $this->redirectToRoute('currency_show', ['id' => $currency->getId()]);
        }

        return [
            'currency' => $currency,
            'form' => $form->createView(),
        ];
    }

    /**
     * @return RedirectResponse
     */
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Route(path: '/{id}', name: 'currency_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, Request $request, Currency $currency) {
        if ($this->isCsrfTokenValid('delete' . $currency->getId(), $request->request->get('_token'))) {
            $entityManager->remove($currency);
            $entityManager->flush();
            $this->addFlash('success', 'The currency has been deleted.');
        }

        return $this->redirectToRoute('currency_index');
    }
}
