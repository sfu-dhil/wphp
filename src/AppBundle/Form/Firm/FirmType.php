<?php

namespace AppBundle\Form\Firm;

use AppBundle\Entity\Firm;
use AppBundle\Entity\Geonames;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Form definition for the firm class.
 */
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
                'help_block' => 'Most complete name of the firm known',
            ),
        ));
        $builder->add('gender', ChoiceType::class, array(
            'expanded' => true,
            'multiple' => false,
            'choices' => array(
                'Female' => Firm::FEMALE,
                'Male' => Firm::MALE,
                'Unknown' => Firm::UNKNOWN,
            ),
            'empty_data' => Firm::UNKNOWN,
        ));
        $builder->add('streetAddress', null, array(
            'label' => 'Street Address',
            'required' => false,
            'attr' => array(
                'help_block' => 'Street address of the firm as geotagged (a new firm entry is made when relocated)',
            ),
        ));
        $builder->add('city', Select2EntityType::class, array(
            'multiple' => false,
            'remote_route' => 'geonames_typeahead',
            'class' => Geonames::class,
            'primary_key' => 'geonameid',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
            'attr' => array(
              'help_block' => 'City/town/village in which the firm’s street address is located; geotagged'
            ),
        ));
        $builder->add('startDate', null, array(
            'label' => 'Start Date',
            'required' => false,
            'attr' => array(
                'help_block' => 'If known, YYYY-MM-DD',
            ),
        ));
        $builder->add('endDate', null, array(
            'label' => 'End Date',
            'required' => false,
            'attr' => array(
                'help_block' => 'If known, YYYY-MM-DD',
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
                'help_block' => 'Indicates the final attempt to find further information about the firm',
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
