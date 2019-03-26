<?php

namespace AppBundle\Controller;

use AppBundle\Repository\FirmRepository;
use AppBundle\Repository\PersonRepository;
use AppBundle\Repository\TitleRepository;
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
    }

    /**
     * @Route("/privacy", name="privacy")
     * @Template()
     */
    public function privacyAction(Request $request)
    {
    }

}
