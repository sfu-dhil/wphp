<?php

namespace AppBundle\Controller;

use AppBundle\Entity\EstcMarc;
use AppBundle\Entity\OsborneMarc;
use AppBundle\Entity\Title;
use AppBundle\Form\Title\TitleSearchType;
use AppBundle\Form\Title\TitleType;
use AppBundle\Repository\TitleRepository;
use AppBundle\Services\EstcMarcImporter;
use AppBundle\Services\SourceLinker;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Title controller.
 *
 * @Route("/title")
 */
class TitleController extends Controller implements PaginatorAwareInterface {

    use PaginatorTrait;

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
        $dql = 'SELECT e FROM AppBundle:Title e';
        $query = $em->createQuery($dql);

        $form = $this->createForm(TitleSearchType::class, null, array(
            'action' => $this->generateUrl('title_search'),
            'entity_manager' => $em
        ));
        $titles = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25, array(
            'defaultSortFieldName' => ['e.title', 'e.pubdate'],
            'defaultSortDirection' => 'asc',
        ));
        return array(
            'search_form' => $form->createView(),
            'titles' => $titles,
            'sortable' => true,
        );
    }

    /**
     * @param Request $request
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Route("/typeahead", name="title_typeahead")
     * @Method("GET")
     * @return JsonResponse
     */
    public function typeaheadAction(Request $request, TitleRepository $repo) {
        $q = $request->query->get('q');
        if (!$q) {
            return new JsonResponse([]);
        }
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
    public function searchAction(Request $request, TitleRepository $repo) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(TitleSearchType::class, null, array('entity_manager' => $em));
        $form->handleRequest($request);
        $titles = array();
        $submitted = false;

        if ($form->isValid()) {
            $data = array_filter($form->getData());
            if (count($data) > 2) {
                $submitted = true;
                $query = $repo->buildSearchQuery($data);
                $titles = $this->paginator->paginate($query->execute(), $request->query->getInt('page', 1), 25);
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
    public function searchExportAction(Request $request, TitleRepository $repo) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(TitleSearchType::class, null, array('entity_manager' => $em));
        $form->handleRequest($request);
        $titles = array();

        if ($form->isValid()) {
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
            foreach ($title->getTitleFirmroles() as $tfr) {
                $tfr->setTitle($title);
                $em->persist($tfr);
            }

            // check for new titleFirmRoles and persist them.
            foreach ($title->getTitleroles() as $tr) {
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
     * Build a new title form prepopulated with data from a MARC record.
     *
     * @param Request $request
     * @Route("/import/{id}", name="title_marc_import")
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Template("AppBundle:Title:new.html.twig")
     * @Method("GET")
     */
    public function importMarcAction(Request $request, EstcMarcImporter $importer, $id) {
        $title = $importer->import($id);
        foreach($importer->getMessages() as $message) {
            $this->addFlash('warning', $message);
        }
        $importer->resetMessages();

        $form = $this->createForm(TitleType::class, $title, array(
            'action' => $this->generateUrl('title_new'),
        ));
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
        return array(
            'title' => $title,
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
        foreach ($title->getTitleFirmroles() as $tfr) {
            $titleFirmRoles->add($tfr);
        }

        $titleRoles = new ArrayCollection();
        foreach ($title->getTitleroles() as $tr) {
            $titleRoles->add($tr);
        }

        $editForm = $this->createForm(TitleType::class, $title);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            // check for deleted titleFirmRoles and remove them.
            foreach ($titleFirmRoles as $tfr) {
                if (!$title->getTitleFirmroles()->contains($tfr)) {
                    $em->remove($tfr);
                }
            }

            // check for deleted titleRoles and remove them.
            foreach ($titleRoles as $tfr) {
                if (!$title->getTitleroles()->contains($tr)) {
                    $em->remove($tr);
                }
            }

            // check for new titleFirmRoles and persist them.
            foreach ($title->getTitleFirmroles() as $tfr) {
                if (!$titleFirmRoles->contains($tfr)) {
                    $tfr->setTitle($title);
                    $em->persist($tfr);
                }
            }

            // check for new titleFirmRoles and persist them.
            foreach ($title->getTitleroles() as $tr) {
                if (!$titleRoles->contains($tr)) {
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
     * Displays a form to edit an existing Title entity.
     *
     * @Route("/{id}/copy", name="title_copy")
     * @Method({"GET", "POST"})
     * @Template()
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @param Request $request
     * @param Title $title
     */
    public function copyAction(Request $request, Title $title, EntityManagerInterface $em) {
        $form = $this->createForm(TitleType::class, $title, array(
            'action' => $this->generateUrl('title_new'),
        ));

        return array(
            'title' => $title,
            'form' => $form->createView(),
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
