<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Jackson;
use App\Repository\JacksonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Jackson controller.
 *
 * @Security("is_granted('ROLE_USER')")
 * @Route("/resource/jackson")
 */
class JacksonController extends AbstractController implements PaginatorAwareInterface
{
    use PaginatorTrait;

    /**
     * Lists all Jackson entities.
     *
     * @return array
     *
     * @Route("/", name="resource_jackson_index", methods={"GET"})
     *
     * @Template
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(Jackson::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();
        $jacksons = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'jacksons' => $jacksons,
        ];
    }

    /**
     * Search for Jackson entities.
     *
     * @return array
     * @Route("/search", name="resource_jackson_search", methods={"GET"})
     * @Template
     */
    public function searchAction(Request $request, JacksonRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $jacksons = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $jacksons = [];
        }

        return [
            'jacksons' => $jacksons,
            'q' => $q,
        ];
    }

    /**
     * Finds and displays a Jackson entity.
     *
     * @return array
     *
     * @Route("/{id}", name="resource_jackson_show", methods={"GET"})
     *
     * @Template
     */
    public function showAction(Jackson $jackson) {
        return [
            'jackson' => $jackson,
        ];
    }
}
