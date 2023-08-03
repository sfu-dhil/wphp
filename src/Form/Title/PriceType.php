<?php

declare(strict_types=1);

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
            'help' => 'title.search.pricePound',
        ]);
        $builder->add('price_shilling', TextType::class, [
            'label' => 'Price (s)',
            'required' => false,
            'help' => 'title.search.priceShilling',
        ]);
        $builder->add('price_pence', TextType::class, [
            'label' => 'Price (p)',
            'required' => false,
            'help' => 'title.search.pricePence',
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
            'help' => 'title.search.priceCompare',
        ]);
    }
}
