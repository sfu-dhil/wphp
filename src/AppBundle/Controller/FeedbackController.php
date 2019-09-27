<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Feedback;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Feedback controller.
 *
 * This one is unusual. The new action is public, but the show and index actions are restricted.
 *
 * @Route("/feedback")
 */
class FeedbackController extends Controller  implements PaginatorAwareInterface {

    use PaginatorTrait;

    /**
     * Lists all Feedback entities.
     *
     * @Route("/", name="feedback_index", methods={"GET"})
     * @Template()
     * @Security("has_role('ROLE_COMMENT_ADMIN')")
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Feedback e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $feedbacks = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return array(
            'feedbacks' => $feedbacks,
        );
    }

    /**
     * Creates a new Feedback entity.
     *
     * @Route("/new", name="feedback_new", methods={"GET","POST"})

     * @Template()
     * @Security("not (has_role('ROLE_USER'))")
     * @param Request $request
     *
     * @return array
     */
    public function newAction(Request $request) {
        $feedback = new Feedback();
        $form = $this->createForm('AppBundle\Form\FeedbackType', $feedback);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($feedback);
            $em->flush();

            $this->addFlash('success', 'The new feedback was created.');
            return $this->redirectToRoute('homepage');
        }

        return array(
            'feedback' => $feedback,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Feedback entity.
     *
     * @Route("/{id}", name="feedback_show", methods={"GET"})
     * @Template()
     * @Security("has_role('ROLE_COMMENT_ADMIN')")
     * @param Feedback $feedback
     *
     * @return array
     */
    public function showAction(Feedback $feedback) {
        return array(
            'feedback' => $feedback,
        );
    }

}
