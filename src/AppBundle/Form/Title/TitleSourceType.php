<?php

namespace AppBundle\Form\Title;

use AppBundle\Entity\Source;
use AppBundle\Entity\TitleSource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Subform definition for assigning sources to titles.
 */
class TitleSourceType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('source', Select2EntityType::class, array(
            'multiple' => false,
            'remote_route' => 'source_typeahead',
            'class' => Source::class,
            'primary_key' => 'id',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
            'attr' => array(
                'help_block' => 'source.form.name'
            )
        ));

        $builder->add('identifier', TextType::class, array(
            'attr' => [
                'help_block' => 'source.form.identifier'
            ]
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => TitleSource::class,
        ));
    }
}
