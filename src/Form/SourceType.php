<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Source;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form definition for the source class.
 */
class SourceType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('name', null, [
            'label' => 'Name',
            'required' => false,
        ]);
        $builder->add('onlineSource', UrlType::class, [
            'label' => 'Online Source',
            'required' => false,
            'help' => 'Optional. Enter the URL for the source.',
        ]);
        $builder->add('description', TextareaType::class, [
            'label' => 'Description',
            'required' => false,
            'help' => 'Provide a short description of the source.',
        ]);
        $builder->add('citation', TextareaType::class, [
            'label' => 'Citation',
            'required' => false,
            'help' => 'Provide citation for the source.',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => Source::class,
        ]);
    }
}
