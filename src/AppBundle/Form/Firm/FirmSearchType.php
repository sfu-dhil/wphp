<?php

namespace AppBundle\Form\Firm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The firmSearchtype is a search form for firms.
 */
class FirmSearchType extends AbstractType {

    /**
     * Build the form.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->setMethod('get');
        $em = $options['entity_manager'];

        $builder->add('name', TextType::class, array(
            'label' => 'Name',
            'required' => false,
            'attr' => array(
                'help_block' => 'Enter all or part of a firm name'
            ),
        ));

        $builder->add('address', TextType::class, array(
            'label' => 'Address',
            'required' => false,
            'attr' => array(
                'help_block' => 'Text search for a firm address'
            ),
        ));

        $builder->add('city', TextType::class, array(
            'label' => 'City',
            'required' => false,
            'attr' => array(
                'help_block' => 'Text search for a firm city'
            ),
        ));
    }

    /**
     * {@inheritdoc}
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        parent::configureOptions($resolver);
        $resolver->setRequired(array('entity_manager'));
    }

}
