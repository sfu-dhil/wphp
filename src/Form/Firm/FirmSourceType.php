<?php

declare(strict_types=1);

namespace App\Form\Firm;

use App\Entity\FirmSource;
use App\Entity\Source;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Subform definition for assigning sources to firms.
 */
class FirmSourceType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('source', Select2EntityType::class, [
            'label' => 'Source',
            'multiple' => false,
            'remote_route' => 'source_typeahead',
            'class' => Source::class,
            'primary_key' => 'id',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
            'required' => true,
            'help' => 'source.form.name',
            'attr' => [
                'required' => true,
            ],
            'placeholder' => 'Search for an existing source by name',
        ]);

        $builder->add('identifier', TextType::class, [
            'label' => 'Identifier',
            'required' => true,
            'help' => 'source.form.identifier',
            'attr' => [
                'required' => true,
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => FirmSource::class,
        ]);
    }
}
