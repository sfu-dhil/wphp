<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Title;
use AppBundle\Form\Title\TitleSearchType;
use AppBundle\Form\Title\TitleType;
use AppBundle\Services\SourceLinker;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @return array
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(TitleSearchType::class, null, array(
            'action' => $this->generateUrl('title_search'),
            'entity_manager' => $em
        ));
        $repo = $em->getRepository(Title::class);
        $query = $repo->indexQuery();
        $paginator = $this->get('knp_paginator');
        $titles = $paginator->paginate($query, $request->query->getint('page', 1), 25);
        return array(
            'search_form' => $form->createView(),
            'titles' => $titles,
        );
    }

    /**
     * @param Request $request
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Route("/typeahead", name="title_typeahead")
     * @Method("GET")
     * @return JsonResponse
     */
    public function typeaheadAction(Request $request) {
        $q = $request->query->get('q');
        if (!$q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Title::class);
        $data = [];
        foreach ($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => $result->getTitle(),
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Export a CSV with the titles.
     *
     * @Route("/export", name="title_export")
     * @Method("GET")
     * @return BinaryFileResponse
     */
    public function exportAction() {
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
        $submitted = false;

        if ($form->isValid()) {
            $data = array_filter($form->getData());
            if (count($data) > 2) {
                $submitted = true;
                $repo = $em->getRepository(Title::class);
                $query = $repo->buildSearchQuery($data);
                $paginator = $this->get('knp_paginator');
                $titles = $paginator->paginate($query, $request->query->getint('page', 1), 25);
            }
        }
        return array(
            'search_form' => $form->createView(),
            'titles' => $titles,
            'submitted' => $submitted,
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
     * Creates a new Title entity.
     *
     * @Route("/new", name="title_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
        $title = new Title();
        $form = $this->createForm(TitleType::class, $title);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // check for new titleFirmRoles and persist them.
            foreach($title->getTitleFirmroles() as $tfr) {
                $tfr->setTitle($title);
                $em->persist($tfr);
            }

            // check for new titleFirmRoles and persist them.
            foreach($title->getTitleroles() as $tr) {
                $tr->setTitle($title);
                $em->persist($tr);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($title);
            $em->flush();

            $this->addFlash('success', 'The new title was created.');
            return $this->redirectToRoute('title_show', array('id' => $title->getId()));
        }

        return array(
            'title' => $title,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Title entity.
     *
     * @Route("/{id}.{_format}", name="title_show", defaults={"_format": "html"})
     * @Method("GET")
     * @Template()
     * @param Title $title
     * @return array
     */
    public function showAction(Title $title, SourceLinker $linker) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Title');
        return array(
            'title' => $title,
            'next' => $repo->next($title),
            'previous' => $repo->previous($title),
            'linker' => $linker,
        );
    }

    /**
     * Displays a form to edit an existing Title entity.
     *
     * @Route("/{id}/edit", name="title_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @param Request $request
     * @param Title $title
     */
    public function editAction(Request $request, Title $title, EntityManagerInterface $em) {
        // collect the titleFirmRole objects before modification.
        $titleFirmRoles = new ArrayCollection();
        foreach($title->getTitleFirmroles() as $tfr) {
            $titleFirmRoles->add($tfr);
        }

        $titleRoles = new ArrayCollection();
        foreach($title->getTitleroles() as $tr) {
            $titleRoles->add($tr);
        }

        $editForm = $this->createForm(TitleType::class, $title);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            // check for deleted titleFirmRoles and remove them.
            foreach($titleFirmRoles as $tfr) {
                if( ! $title->getTitleFirmroles()->contains($tfr)) {
                    $em->remove($tfr);
                }
            }

            // check for deleted titleRoles and remove them.
            foreach($titleRoles as $tfr) {
                if( ! $title->getTitleroles()->contains($tr)) {
                    $em->remove($tr);
                }
            }

            // check for new titleFirmRoles and persist them.
            foreach($title->getTitleFirmroles() as $tfr) {
                if( ! $titleFirmRoles->contains($tfr)) {
                    $tfr->setTitle($title);
                    $em->persist($tfr);
                }
            }

            // check for new titleFirmRoles and persist them.
            foreach($title->getTitleroles() as $tr) {
                if( ! $titleRoles->contains($tr)) {
                    $tr->setTitle($title);
                    $em->persist($tr);
                }
            }

            $em->flush();
            $this->addFlash('success', 'The title has been updated.');
            return $this->redirectToRoute('title_show', array('id' => $title->getId()));
        }

        return array(
            'title' => $title,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Title entity.
     *
     * @Route("/{id}/delete", name="title_delete")
     * @Method("GET")
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @param Request $request
     * @param Title $title
     */
    public function deleteAction(Request $request, Title $title) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($title);
        $em->flush();
        $this->addFlash('success', 'The title was deleted.');

        return $this->redirectToRoute('title_index');
    }

}
