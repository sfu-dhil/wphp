<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GeonamesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder->add('name');     
        $builder->add('asciiname');     
        $builder->add('alternatenames');     
        $builder->add('latitude');     
        $builder->add('longitude');     
        $builder->add('fclass');     
        $builder->add('fcode');     
        $builder->add('country');     
        $builder->add('cc2');     
        $builder->add('admin1');     
        $builder->add('admin2');     
        $builder->add('admin3');     
        $builder->add('admin4');     
        $builder->add('population');     
        $builder->add('elevation');     
        $builder->add('gtopo30');     
        $builder->add('timezone');     
        $builder->add('moddate');         
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Geonames'
        ));
    }
}
