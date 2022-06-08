<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form\Title;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Price form definitions.
 */
class PriceType extends AbstractType {
    /**
     * Build the form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('price_pound', TextType::class, [
            'label' => 'Price (£)',
            'required' => false,
            'attr' => [
                'help_block' => 'title.search.pricePound',
            ],
        ]);
        $builder->add('price_shilling', TextType::class, [
            'label' => 'Price (s)',
            'required' => false,
            'attr' => [
                'help_block' => 'title.search.priceShilling',
            ],
        ]);
        $builder->add('price_pence', TextType::class, [
            'label' => 'Price (p)',
            'required' => false,
            'attr' => [
                'help_block' => 'title.search.pricePence',
            ],
        ]);
        $builder->add('price_comparison', ChoiceType::class, [
            'label' => 'Comparison',
            'required' => true,
            'choices' => [
                'Equal to' => 'eq',
                'Less than' => 'lt',
                'Greater than' => 'gt',
            ],
            'expanded' => false,
            'multiple' => false,
            'attr' => [
                'help_block' => 'title.search.priceCompare',
            ],
        ]);
    }
}
