<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Feedback form definition.
 */
class FeedbackType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('name');
        $builder->add('email', EmailType::class);
        $builder->add('content', TextareaType::class, [
            'label' => 'Comment',
            'required' => true,
            'attr' => [
                'help_block' => 'What would you like us to know?',
                'class' => 'tinymce',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Feedback',
        ]);
    }
}
