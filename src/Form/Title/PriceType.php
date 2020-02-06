<?php

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
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('price_pound', TextType::class, array(
            'label' => 'Price (£)',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.fields.pricePound',
            ),
        ));
        $builder->add('price_shilling', TextType::class, array(
            'label' => 'Price (s)',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.fields.priceShilling',
            ),
        ));
        $builder->add('price_pence', TextType::class, array(
            'label' => 'Price (p)',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.fields.pricePence',
            ),
        ));
        $builder->add('price_comparison', ChoiceType::class, array(
            'label' => 'Comparison',
            'required' => true,
            'choices' => array(
                'Equal to' => 'eq',
                'Less than' => 'lt',
                'Greater than' => 'gt',
            ),
            'expanded' => false,
            'multiple' => false,
            'attr' => array(
                'help_block' => 'How to compare this price against the record prices',
            ),
        ));
    }
}
