<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormatType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        $builder->add('name', null, array(
            'label' => 'Name',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
                $builder->add('abbrevOne', null, array(
            'label' => 'Abbrev One',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
                $builder->add('abbrevTwo', null, array(
            'label' => 'Abbrev Two',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
                $builder->add('abbrevThree', null, array(
            'label' => 'Abbrev Three',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
                $builder->add('abbrevFour', null, array(
            'label' => 'Abbrev Four',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
                
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Format'
        ));
    }
}
