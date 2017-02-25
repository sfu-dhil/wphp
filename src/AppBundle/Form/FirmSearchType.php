<?php

namespace AppBundle\Form;

use AppBundle\Entity\Format;
use AppBundle\Entity\Genre;
use AppBundle\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of TitleSearchType
 */
class FirmSearchType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->setMethod('get');
        $em = $options['entity_manager'];
        
        $builder->add('name', TextType::class, array(
            'label' => 'Name',
            'required' => false,
            'attr' => array(
                'help_block' => 'Enter all or part of a firm name.'
            ),
        ));
        
        $builder->add('address', TextType::class, array(
            'label' => 'Address',
            'required' => false,
            'attr' => array(
                'group_class' => 'hidden secondary',
            ),
        ));
        
        $builder->add('city', TextType::class, array(
            'label' => 'City',
            'required' => false,
            'attr' => array(
                'group_class' => 'hidden secondary',
            ),
        ));

        $builder->add('start', TextType::class, array(
            'label' => 'Start Year',
            'required' => false,
            'attr' => array(
                'help_block' => 'Enter a year (eg <kbd>1795</kbd>) or range of years (<kbd>1790-1800</kbd>) or a partial range of years (<kbd>*-1800</kbd>).',
                'group_class' => 'hidden secondary',
            ),
        ));
        
        $builder->add('end', TextType::class, array(
            'label' => 'End Year',
            'required' => false,
            'attr' => array(
                'help_block' => 'Enter a year (eg <kbd>1795</kbd>) or range of years (<kbd>1790-1800</kbd>) or a partial range of years (<kbd>*-1800</kbd>).',
                'group_class' => 'hidden secondary',
            ),
        ));
    }

    public function configureOptions(OptionsResolver $resolver) {
        parent::configureOptions($resolver);
        $resolver->setRequired(array('entity_manager'));
    }

}
