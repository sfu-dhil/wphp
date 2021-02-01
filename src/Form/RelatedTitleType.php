<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use App\Entity\RelatedTitle;
use App\Entity\Title;
use App\Entity\TitleRelationship;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * RelatedTitle form.
 */
class RelatedTitleType extends AbstractType {
    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
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
     */
    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => RelatedTitle::class,
        ]);
    }
}
