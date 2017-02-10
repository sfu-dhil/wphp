<?php

namespace Nines\BlogBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder->add('title');    
        $builder->add('excerpt', CKEditorType::class, array(
            'config_name' => 'wphp_config',
            'attr' => array(
                'help_block' => 'Excerpts will be shown on the home page and in '
                . 'lists of blog posts. Leave this field blank and one will be '
                . 'generated automatically.'
            ),
        ));
        $builder->add('content', CKEditorType::class, array(
            'config_name' => 'wphp_config',
        ));     
        $builder->add('category');     
        $builder->add('status');         
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Nines\BlogBundle\Entity\Post'
        ));
    }
}
