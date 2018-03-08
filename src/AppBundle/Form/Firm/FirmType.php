<?php

namespace AppBundle\Form\Firm;

use AppBundle\Entity\Firm;
use AppBundle\Entity\Geonames;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class FirmType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name', null, array(
            'label' => 'Name',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('streetAddress', null, array(
            'label' => 'Street Address',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('city', Select2EntityType::class, array(
            'multiple' => false,
            'remote_route' => 'geonames_typeahead',
            'class' => Geonames::class,
            'primary_key' => 'geonameid',
            'text_property' => 'name',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',            
        ));
        $builder->add('startDate', null, array(
            'label' => 'Start Date',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('endDate', null, array(
            'label' => 'End Date',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('finalcheck', ChoiceType::class, array(
            'label' => 'Firm Finalcheck',
            'expanded' => true,
            'multiple' => false,
            'choices' => array(
                'Yes' => true,
                'No' => false,
            ),
            'required' => true,
            'placeholder' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Firm::class
        ));
    }

}
