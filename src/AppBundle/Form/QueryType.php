<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of QueryType
 *
 * @author mjoyce
 */
class QueryType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('target', ChoiceType::class, array(
            'choices' => array(
                'Author' => 'author',
                'Title' => 'title',
                'Firm' => 'firm',
            ),
            'attr' => array(
                'help_block' => 'Select the type of result you are looking for.'
            ),
            'label' => 'Search type',
            'required' => true,
            'expanded' => false,
            'multiple' => false,
        ));

        $builder->add('title_filters', TitleFilterType::class, array(
            'required' => false
        ));
        $builder->add('person_filters', PersonFilterType::class, array(
            'required' => false
        ));
        $builder->add('firm_filters', FirmFilterType::class, array(
            'required' => false
        ));
    }
}
