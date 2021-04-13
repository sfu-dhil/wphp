<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form\Title;

use App\Entity\Source;
use App\Entity\TitleSource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Subform definition for assigning sources to titles.
 */
class TitleSourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('source', Select2EntityType::class, [
            'multiple' => false,
            'remote_route' => 'source_typeahead',
            'class' => Source::class,
            'primary_key' => 'id',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
            'required' => true,
            'attr' => [
                'help_block' => 'source.form.name',
                'required' => true,
            ],
        ]);

        $builder->add('identifier', TextType::class, [
            'required' => true,
            'attr' => [
                'help_block' => 'source.form.identifier',
                'required' => true,
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => TitleSource::class,
        ]);
    }
}
