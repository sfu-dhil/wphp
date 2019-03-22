<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Jackson;
use AppBundle\Repository\JacksonRepository;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Jackson controller.
 *
 * @Security("has_role('ROLE_USER')")
 * @Route("/resource/jackson")
 */
class JacksonController extends Controller  implements PaginatorAwareInterface {

    use PaginatorTrait;

    /**
     * Lists all Jackson entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="resource_jackson_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(Jackson::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();
        $jacksons = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return array(
            'jacksons' => $jacksons,
        );
    }

    /**
     * Search for Jackson entities.
     *
     * To make this work, add a method like this one to the
     * AppBundle:Jackson repository. Replace the fieldName with
     * something appropriate, and adjust the generated search.html.twig
     * template.
     *
     * @return array
     *
     * <code><pre>
     *    public function searchQuery($q) {
     *       $qb = $this->createQueryBuilder('e');
     *       $qb->addSelect("MATCH (e.title) AGAINST(:q BOOLEAN) as HIDDEN score");
     *       $qb->orderBy('score', 'DESC');
     *       $qb->setParameter('q', $q);
     *       return $qb->getQuery();
     *    }
     * </pre></code>
     *
     * @param Request $request
     *
     * @Route("/search", name="resource_jackson_search")
     * @Method("GET")
     * @Template()
     */
    public function searchAction(Request $request, JacksonRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $jacksons = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $jacksons = array();
        }

        return array(
            'jacksons' => $jacksons,
            'q' => $q,
        );
    }

    /**
     * Finds and displays a Jackson entity.
     *
     * @param Jackson $jackson
     *
     * @return array
     *
     * @Route("/{id}", name="resource_jackson_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Jackson $jackson) {

        return array(
            'jackson' => $jackson,
        );
    }

}
