<?php

namespace AppBundle\Controller;

use GuzzleHttp\Exception\BadResponseException;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use League\Flysystem\FileNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Default controller for the home page.
 */
class DefaultController extends Controller implements PaginatorAwareInterface
{

    use PaginatorTrait;

    /**
     * Home page.
     *
     * @Route("/", name="homepage")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $postQuery = $em->getRepository('NinesBlogBundle:Post')->recentQuery(
            false,
            $this->getParameter('nines_blog.homepage_posts')
        );
        $blocksize = $this->getParameter('wphp.homepage_entries');
        $titles = $em->getRepository('AppBundle:Title')->random($blocksize);
        $persons = $em->getRepository('AppBundle:Person')->random($blocksize);
        $firms = $em->getRepository('AppBundle:Firm')->random($blocksize);

        return [
            'posts' => $postQuery->execute(),
            'titles' => $titles,
            'persons' => $persons,
            'firms' => $firms,
        ];
    }

    /**
     * @Route("/privacy", name="privacy")
     * @Template()
     */
    public function privacyAction(Request $request)
    {

    }

    /**
     * @param Request $request
     * @param string $path
     * @Route("/editor/uploads/{path}", name="editor_image", requirements={"path"=".+"})
     */
    public function editorUpload(Request $request, $path)
    {
        $base = $this->getParameter('kernel.project_dir');
        $root = $base . '/' . $this->getParameter('wphp.ckfinder_root');
        $file = realpath($root . '/' . $path);
        if(substr($file, 0, strlen($root)) !== $root) {
            throw new FileNotFoundException("The requested file was not found.");
        }
        return new BinaryFileResponse($file);
    }

    /**
     * @param Request $request
     * @Route("/upload/image", name="editor_upload")
     */
    public function editorUploadAction(Request $request) {
        if($request->files->count() != 1) {
            throw new BadRequestHttpException("Expected one file parameter. Got " . $request->files->count() . " instead.");
        }

        $uploadDir = $this->getParameter('kernel.project_dir') . '/web/tinymce';
        $uploadFile = $request->files->get('file');

        $clientName = preg_replace("/[^a-z0-9 _-]/i", '', $uploadFile->getClientOriginalName());
        $name = uniqid($clientName . '_') . '.' . $uploadFile->guessExtension();
        $uploadFile->move($uploadDir, $name);

        return new JsonResponse(array('location' => $this->generateUrl('editor_image', array('filename' => $name), UrlGeneratorInterface::ABSOLUTE_PATH)));
    }

    /**
     * @param Request $request
     * @Route("/upload/image/{filename}", name="editor_image")
     */
    public function editorImageAction(Request $request, $filename) {
        if( ! preg_match('/^[a-z0-9 ._-]*$/i', $filename)) {
            throw new BadRequestHttpException('Invalid file name: ' . $filename);
        }
        $uploadDir = $this->getParameter('kernel.project_dir') . '/web/tinymce';
        $path = $uploadDir . '/' . $filename;
        if( ! file_exists($path)) {
            throw new FileNotFoundException('Cannot find image file at ' . $path);
        }
        return new BinaryFileResponse($path);
    }

}
