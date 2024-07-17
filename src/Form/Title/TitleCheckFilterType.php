<?php

declare(strict_types=1);

namespace App\Form\Title;

use App\Entity\Title;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Search form for person entities.
 */
class TitleCheckFilterType extends AbstractType {
    /**
     * Build the form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->setMethod('get');
        $builder->add('finalattempt', ChoiceType::class, [
            'label' => 'Attempted Verification',
            'expanded' => false,
            'multiple' => false,
            'choices' => [
                '' => null,
                'Yes' => true,
                'No' => false,
            ],
            'required' => false,
            'placeholder' => false,
        ]);
        $builder->add('finalcheck', ChoiceType::class, [
            'label' => 'Verified',
            'expanded' => false,
            'multiple' => false,
            'choices' => [
                '' => null,
                'Yes' => true,
                'No' => false,
            ],
            'required' => false,
            'placeholder' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => Title::class,
        ]);
    }
}
