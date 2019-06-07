<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Firm;
use AppBundle\Form\Firm\FirmSearchType;
use AppBundle\Form\Firm\FirmType;
use AppBundle\Repository\FirmRepository;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Firm controller.
 *
 * @Route("/firm")
 */
class FirmController extends Controller implements PaginatorAwareInterface {

    use PaginatorTrait;

    /**
     * Lists all Firm entities.
     *
     * @Route("/", name="firm_index", methods={"GET"})

     * @Template()
     *
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request) {
        $form = $this->createForm(FirmSearchType::class, null, array(
            'action' => $this->generateUrl('firm_search'),
        ));
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Firm e';
        if ($request->query->get('sort') === 'g.name+e.name') {
            $dql = 'SELECT e FROM AppBundle:Firm e LEFT JOIN e.city g ORDER BY e.name, e.startDate';
        }
        $query = $em->createQuery($dql);
        $firms = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25, array(
            'defaultSortFieldName' => ['e.name', 'e.startDate'],
            'defaultSortDirection' => 'asc',
        ));
        return array(
            'search_form' => $form->createView(),
            'firms' => $firms,
            'sortable' => true,
        );
    }

    /**
     * @param Request $request
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Route("/typeahead", name="firm_typeahead", methods={"GET"})

     * @return JsonResponse
     */
    public function typeaheadAction(Request $request, FirmRepository $repo) {
        $q = $request->query->get('q');
        if (!$q) {
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
     * Full text search for Firm entities.
     *
     * @Route("/search", name="firm_search", methods={"GET"})

     * @Template()
     * @param Request $request
     * @return array
     */
    public function searchAction(Request $request, FirmRepository $repo) {
        $form = $this->createForm(FirmSearchType::class);
        $form->handleRequest($request);
        $firms = array();
        $submitted = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $submitted = true;
            $query = $repo->buildSearchQuery($form->getData());
            $firms = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        }
        return array(
            'search_form' => $form->createView(),
            'firms' => $firms,
            'submitted' => $submitted,
        );
    }

    /**
     * Full text search export for Title entities.
     *
     * @Route("/search/export", name="firm_search_export", methods={"GET"})
     * @param Request $request
     * @return array
     */
    public function searchExportAction(Request $request, FirmRepository $repo) {
        $form = $this->createForm(FirmSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $submitted = true;
            $query = $repo->buildSearchQuery($form->getData());

            $iterator = $query->iterate();
            $tmpPath = tempnam(sys_get_temp_dir(), 'wphp-export-');
            $fh = fopen($tmpPath, 'w');
            fputcsv($fh, array('ID', 'Name', 'Street Address', 'City', 'Start Date', 'End Date'));
            foreach($iterator as $row) {
                $firm = $row[0];
                fputcsv($fh, array(
                    $firm->getId(),
                    $firm->getName(),
                    $firm->getStreetAddress(),
                    $firm->getCity()->getName(),
                    preg_replace('/-00/', '', $firm->getStartDate()),
                    preg_replace('/-00/', '', $firm->getEndDate()),
                ));
            }
            fclose($fh);
            $response = new BinaryFileResponse($tmpPath);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'wphp-firms.csv');
            $response->deleteFileAfterSend(true);
            return $response;
        }
        $this->addFlash('error', 'form not submitted.');
        return $this->redirectToRoute('firm_search');
    }

    /**
     * Search for Title entities.
     *
     * @Route("/jump", name="firm_jump", methods={"GET"})

     * @Template()
     * @param Request $request
     */
    public function jumpAction(Request $request) {
        $q = $request->query->get('q');
        if ($q) {
            return $this->redirect($this->generateUrl('firm_show', array('id' => $q)));
        } else {
            return $this->redirect($this->generateUrl('firm_index'));
        }
    }

    /**
     * Creates a new Firm entity.
     *
     * @Route("/new", name="firm_new", methods={"GET","POST"})

     * @Template()
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @param Request $request
     */
    public function newAction(Request $request) {
        $firm = new Firm();
        $form = $this->createForm(FirmType::class, $firm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($firm);
            $em->flush();

            $this->addFlash('success', 'The new firm was created.');
            return $this->redirectToRoute('firm_show', array('id' => $firm->getId()));
        }

        return array(
            'firm' => $firm,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Firm entity.
     *
     * @Route("/{id}.{_format}", name="firm_show", defaults={"_format": "html"}, methods={"GET"})
     * @Template()
     * @param Firm $firm
     * @return array
     */
    public function showAction(Request $request, Firm $firm) {
        $em = $this->getDoctrine()->getManager();
        $firmRoles = $firm->getTitleFirmroles(true);
        $pagination = $this->paginator->paginate($firmRoles, $request->query->getInt('page', 1), 25);
        return array(
            'firm' => $firm,
            'pagination' => $pagination,
        );
    }

    /**
     * Displays a form to edit an existing Firm entity.
     *
     * @Route("/{id}/edit", name="firm_edit", methods={"GET","POST"})

     * @Template()
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @param Request $request
     * @param Firm $firm
     */
    public function editAction(Request $request, Firm $firm) {
        $editForm = $this->createForm(FirmType::class, $firm);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The firm has been updated.');
            return $this->redirectToRoute('firm_show', array('id' => $firm->getId()));
        }

        return array(
            'firm' => $firm,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Firm entity.
     *
     * @Route("/{id}/delete", name="firm_delete", methods={"GET"})

     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @param Request $request
     * @param Firm $firm
     */
    public function deleteAction(Request $request, Firm $firm) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($firm);
        $em->flush();
        $this->addFlash('success', 'The firm was deleted.');

        return $this->redirectToRoute('firm_index');
    }

}
