<?php

declare(strict_types=1);

namespace App\Form\Title;

use App\Entity\Firm;
use App\Entity\Firmrole;
use App\Entity\TitleFirmrole;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Subform definition for assigning firms to titles with roles.
 */
class TitleFirmType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('firm', Select2EntityType::class, [
            'label' => 'Firm',
            'multiple' => false,
            'remote_route' => 'firm_typeahead',
            'text_property' => 'getFormId',
            'class' => Firm::class,
            'primary_key' => 'id',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
            'required' => true,
            'help' => 'firm.fields.name',
            'attr' => [
                'required' => true,
            ],
            'placeholder' => 'Search for an existing firm by name',
        ]);

        $builder->add('firmrole', EntityType::class, [
            'label' => 'Role',
            'class' => Firmrole::class,
            'choice_label' => 'name',
            'multiple' => false,
            'expanded' => false,
            'placeholder' => 'Select a firm role',
            'required' => true,
            'help' => 'firm.fields.role',
            'attr' => [
                'required' => true,
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => TitleFirmrole::class,
        ]);
    }
}
