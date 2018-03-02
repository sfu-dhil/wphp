<?php

namespace AppBundle\Form\Firm;

use AppBundle\Entity\Firm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
//        $builder->add('city');
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
