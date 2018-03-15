<?php

namespace AppBundle\Form\Title;

use AppBundle\Entity\Firm;
use AppBundle\Entity\Firmrole;
use AppBundle\Entity\TitleFirmrole;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class TitleFirmType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('Firm', Select2EntityType::class, array(
            'multiple' => false,
            'remote_route' => 'firm_typeahead',
            'class' => Firm::class,
            'primary_key' => 'id',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',            
        ));
        
        $builder->add('firmrole', Select2EntityType::class, array(
            'multiple' => false,
            'remote_route' => 'firmrole_typeahead',
            'class' => Firmrole::class,
            'primary_key' => 'id',
            'text_property' => 'name',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',            
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => TitleFirmrole::class
        ));
    }

}
