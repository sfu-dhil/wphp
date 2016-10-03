<?php

namespace AppBundle\Controller;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction(Request $request) {
        // replace this example code with whatever you need
        return [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..'),
        ];
    }
	
	/**
	 * @Route("/t", name="t")
	 * @Template()
	 */
	public function tAction(Request $request) {
		$formBuilder = $this->createFormBuilder();
		$formBuilder->add('d', CKEditorType::class, array(
			'mapped' => false,
			'config_name' => 'my_config',
			'attr' => array(
				'form_style' => 'label-above',
			),
		));
		$formBuilder->add('save', SubmitType::class);
		$form = $formBuilder->getForm();
		$form->handleRequest($request);
		$content = '';
		if($form->isSubmitted() && $form->isValid()) {
			$content = $form->get('d')->getData();
		}
		return array(
			'form' => $form->createView(),
			'content' => $content
		);
	}

}
