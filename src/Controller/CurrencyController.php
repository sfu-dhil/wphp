<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

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

/**
 * @Route("/currency")
 */
class CurrencyController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @Route("/", name="currency_index", methods={"GET"})
     *
     * @Template
     */
    public function index(Request $request, CurrencyRepository $currencyRepository) : array {
        $query = $currencyRepository->indexQuery();
        $pageSize = $this->getParameter('page_size');
        $page = $request->query->getint('page', 1);

        return [
            'currencies' => $this->paginator->paginate($query, $page, $pageSize),
        ];
    }

    /**
     * @Route("/search", name="currency_search", methods={"GET"})
     *
     * @Template
     */
    public function search(Request $request, CurrencyRepository $currencyRepository) : array {
        $q = $request->query->get('q');
        if ($q) {
            $pageSize = $this->getParameter('page_size');
            $query = $currencyRepository->searchQuery($q);
            $currencies = $this->paginator->paginate($query, $request->query->getInt('page', 1), $pageSize, ['wrap-queries' => true]);
        } else {
            $currencies = [];
        }

        return [
            'currencies' => $currencies,
            'q' => $q,
        ];
    }

    /**
     * @Route("/typeahead", name="currency_typeahead", methods={"GET"})
     */
    public function typeahead(Request $request, CurrencyRepository $currencyRepository) : JsonResponse {
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
     * @Route("/new", name="currency_new", methods={"GET", "POST"})
     * @Template
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new(Request $request, EntityManagerInterface $em) {
        $currency = new Currency();
        $form = $this->createForm(CurrencyType::class, $currency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($currency);
            $em->flush();
            $this->addFlash('success', 'The new currency has been saved.');

            return $this->redirectToRoute('currency_show', ['id' => $currency->getId()]);
        }

        return [
            'currency' => $currency,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}", name="currency_show", methods={"GET"})
     * @Template
     */
    public function show(Currency $currency) : array {
        return [
            'currency' => $currency,
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="currency_edit", methods={"GET", "POST"})
     *
     * @Template
     *
     * @return array|RedirectResponse
     */
    public function edit(Request $request, Currency $currency, EntityManagerInterface $em) {
        $form = $this->createForm(CurrencyType::class, $currency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The updated currency has been saved.');

            return $this->redirectToRoute('currency_show', ['id' => $currency->getId()]);
        }

        return [
            'currency' => $currency,
            'form' => $form->createView(),
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}", name="currency_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Currency $currency, EntityManagerInterface $em) : RedirectResponse {
        if ($this->isCsrfTokenValid('delete' . $currency->getId(), $request->request->get('_token'))) {
            $em->remove($currency);
            $em->flush();
            $this->addFlash('success', 'The currency has been deleted.');
        }

        return $this->redirectToRoute('currency_index');
    }
}
