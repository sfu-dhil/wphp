<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of QueryType
 *
 * @todo is this form actually used anywhere?
 */
class QueryType extends AbstractType
{

    /**
     * Build the form.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
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
