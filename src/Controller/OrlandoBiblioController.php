<?php

namespace App\Controller;

use App\Entity\OrlandoBiblio;
use App\Repository\OrlandoBiblioRepository;
use App\Services\OrlandoManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Nines\UtilBundle\Controller\PaginatorTrait;

/**
 * OrlandoBiblio controller.
 *
 * @Security("is_granted('ROLE_USER')")
 * @Route("/resource/orlando_biblio")
 */
class OrlandoBiblioController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all OrlandoBiblio entities.
     *
     * @param Request $request
     * @param OrlandoManager $manager
     *
     * @return array
     *
     * @Route("/", name="resource_orlando_biblio_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request, OrlandoManager $manager, EntityManagerInterface $em) {
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(OrlandoBiblio::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();
        $orlandoBiblios = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return array(
            'orlandoBiblios' => $orlandoBiblios,
            'manager' => $manager,
        );
    }

    /**
     * Search for OrlandoBiblio entities.
     *
     * @param Request $request
     * @param OrlandoManager $manager
     * @param OrlandoBiblioRepository $repo
     *
     * @return array
     * @Route("/search", name="resource_orlando_biblio_search", methods={"GET"})
     * @Template()
     */
    public function searchAction(Request $request, OrlandoManager $manager, OrlandoBiblioRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $orlandoBiblios = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $orlandoBiblios = array();
        }

        return array(
            'orlandoBiblios' => $orlandoBiblios,
            'q' => $q,
            'manager' => $manager,
        );
    }

    /**
     * Finds and displays a OrlandoBiblio entity.
     *
     * @param OrlandoBiblio $orlandoBiblio
     * @param OrlandoManager $manager
     *
     * @return array
     *
     * @Route("/{id}", name="resource_orlando_biblio_show", methods={"GET"})
     * @Template()
     */
    public function showAction(OrlandoBiblio $orlandoBiblio, OrlandoManager $manager) {
        return array(
            'orlandoBiblio' => $orlandoBiblio,
            'manager' => $manager,
        );
    }
}
