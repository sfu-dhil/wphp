<?php

namespace App\Form;

use App\Entity\RelatedTitle;
use App\Entity\Title;
use App\Entity\TitleRelationship;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * RelatedTitle form.
 */
class RelatedTitleType extends AbstractType {

    /**
     * Add form fields to $builder.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('titleRelationship', Select2EntityType::class, [
            'label' => 'Title Relationship',
            'class' => TitleRelationship::class,
            'remote_route' => 'title_relationship_typeahead',
            'allow_clear' => true,
            'attr' => [
                'help_block' => '',
            ],
        ]);

        $builder->add('relatedTitle', Select2EntityType::class, [
            'label' => 'Title',
            'class' => Title::class,
            'remote_route' => 'title_typeahead',
            'allow_clear' => true,
            'attr' => [
                'help_block' => '',
           ],
        ]);
    }

    /**
     * Define options for the form.
     *
     * Set default, optional, and required options passed to the
     * buildForm() method via the $options parameter.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => RelatedTitle::class,
        ]);
    }

}
