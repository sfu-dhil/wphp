<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Title;
use AppBundle\Form\TitleSearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Title controller.
 *
 * @Route("/title")
 */
class TitleController extends Controller {

    /**
     * Lists all Title entities.
     *
     * @Route("/", name="title_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Title e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $titles = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'titles' => $titles,
        );
    }

    /**
     * Export a CSV with the titles.
     * 
     * @Route("/export", name="title_export")
     * @Method("GET")
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function exportAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Title e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $iterator = $query->iterate();
        $tmpPath = tempnam(sys_get_temp_dir(), 'wphp-export-');
        $fh = fopen($tmpPath, 'w');
        fputcsv($fh, array(
            'id',
            'title',
            'signed_author',
            'pseudonym',
            'imprint',
            'selfpublished',
            'printing_city',
            'printing_country',
            'printing_lat',
            'printing_long',
            'pubdate',
            'format',
            'length',
            'width',
            'edition',
            'volumes',
            'pagination',
            'price_pound',
            'price_shilling',
            'price_pence',
            'genre',
            'shelfmark',
        ));
        foreach ($iterator as $row) {
            $title = $row[0];
            fputcsv($fh, array(
                $title->getId(),
                $title->getTitle(),
                $title->getSignedAuthor(),
                $title->getPseudonym(),
                $title->getImprint(),
                $title->getSelfPublished() ? 'yes' : 'no',
                $title->getLocationOfPrinting()->getName(),
                $title->getLocationOfPrinting()->getCountry(),
                $title->getLocationOfPrinting()->getLatitude(),
                $title->getLocationOfPrinting()->getLongitude(),
                $title->getPubDate(),
                $title->getFormat()->getName(),
                $title->getSizeL(),
                $title->getSizeW(),
                $title->getEdition(),
                $title->getVolumes(),
                $title->getPagination(),
                $title->getPricePound(),
                $title->getPriceShilling(),
                $title->getPricePence(),
                $title->getGenre()->getName(),
                $title->getShelfmark(),
            ));
        }
        fclose($fh);
        $response = new BinaryFileResponse($tmpPath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'wphp-titles.csv');
        $response->deleteFileAfterSend(true);
        return $response;
    }

    /**
     * Full text search for Title entities.
     *
     * @Route("/quick_search", name="title_quick_search")
     * @Method("GET")
     * @Template("AppBundle:Title:search.html.twig")
     * @param Request $request
     * @return array
     */
    public function quickSearchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(TitleSearchType::class, null, array(
            'action' => $this->generateUrl('title_search'),
            'entity_manager' => $em
        ));
        $q = $request->query->get('q');
        $form->get('title')->submit($q);
        $repo = $em->getRepository(Title::class);
        $query = $repo->buildSearchQuery(array('title' => $q));
        $paginator = $this->get('knp_paginator');
        $titles = $paginator->paginate($query->execute(), $request->query->getint('page', 1), 25);
        return array(
            'search_form' => $form->createView(),
            'titles' => $titles,
        );
    }

    /**
     * Search for Title entities.
     *
     * @Route("/jump", name="title_jump")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function jumpAction(Request $request) {
        $q = $request->query->get('q');
        if ($q) {
            return $this->redirect($this->generateUrl('title_show', array('id' => $q)));
        } else {
            return $this->redirect($this->generateUrl('title_index', array('id' => $q)));
        }
    }

    /**
     * Full text search for Title entities.
     *
     * @Route("/search", name="title_search")
     * @Method({"GET"})
     * @Template()
     * @param Request $request
     * @return array
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(TitleSearchType::class, null, array('entity_manager' => $em));
        $form->handleRequest($request);
        $titles = array();

        if ($form->isValid()) {
            $data = array_filter($form->getData());
            if (count($data) > 2) {
                $this->addFlash('success', 'doing search.');
                $repo = $em->getRepository(Title::class);
                $query = $repo->buildSearchQuery($data);
                $paginator = $this->get('knp_paginator');
                $titles = $paginator->paginate($query->execute(), $request->query->getint('page', 1), 25);
            } else {
                $this->addFlash('warning', 'You must enter a search term.');
            }
        }
        return array(
            'search_form' => $form->createView(),
            'titles' => $titles,
        );
    }

    /**
     * Full text search for Title entities.
     *
     * @Route("/search/export", name="title_search_export")
     * @Method({"GET"})
     * @Template()
     * @param Request $request
     * @return array
     */
    public function searchExportAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(TitleSearchType::class, null, array('entity_manager' => $em));
        $form->handleRequest($request);
        $titles = array();

        if ($form->isValid()) {
            $repo = $em->getRepository(Title::class);
            $query = $repo->buildSearchQuery($form->getData());
            $titles = $query->execute();
        }
        return array(
            'titles' => $titles,
            'format' => $request->query->get('format', 'mla'),
        );
    }

    /**
     * Finds and displays a Title entity.
     *
     * @Route("/{id}", name="title_show")
     * @Method("GET")
     * @Template()
     * @param Title $title
     */
    public function showAction(Request $request, Title $title) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Title');
        return array(
            'title' => $title,
            'next' => $repo->next($title),
            'previous' => $repo->previous($title),
        );
    }

}
