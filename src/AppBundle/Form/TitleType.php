<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TitleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder->add('title');     
        $builder->add('signedAuthor');     
        $builder->add('surrogate');     
        $builder->add('pseudonym');     
        $builder->add('imprint');     
        $builder->add('selfpublished');     
        $builder->add('pubdate');     
        $builder->add('dateOfFirstPublication');     
        $builder->add('sizeL');     
        $builder->add('sizeW');     
        $builder->add('edition');     
        $builder->add('volumes');     
        $builder->add('pagination');     
        $builder->add('pricePound');     
        $builder->add('priceShilling');     
        $builder->add('pricePence');     
        $builder->add('sourceId');     
        $builder->add('source2Id');     
        $builder->add('shelfmark');     
        $builder->add('checked');     
        $builder->add('finalcheck');     
        $builder->add('notes');     
        $builder->add('locationOfPrinting');     
        $builder->add('format');     
        $builder->add('genre');     
        $builder->add('source');     
        $builder->add('source2');         
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Title'
        ));
    }
}
