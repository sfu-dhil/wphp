<?php

namespace AppBundle\Controller;

use GuzzleHttp\Exception\BadResponseException;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use League\Flysystem\FileNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

}
