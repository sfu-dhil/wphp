<?php

namespace AppBundle\Form\Title;

use AppBundle\Entity\Title;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TitleType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('title', null, array(
            'label' => 'Title',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('editionNumber', null, array(
            'label' => 'Edition Number',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('signedAuthor', null, array(
            'label' => 'Signed Author',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('surrogate', null, array(
            'label' => 'Surrogate',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('pseudonym', null, array(
            'label' => 'Pseudonym',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('imprint', null, array(
            'label' => 'Imprint',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('selfpublished', ChoiceType::class, array(
            'label' => 'Selfpublished',
            'expanded' => true,
            'multiple' => false,
            'choices' => array(
                'Yes' => true,
                'No' => false,
                'Unknown' => null,
            ),
            'required' => false,
            'placeholder' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('pubdate', null, array(
            'label' => 'Pubdate',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('dateOfFirstPublication', null, array(
            'label' => 'Date Of First Publication',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('sizeL', null, array(
            'label' => 'Size L',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('sizeW', null, array(
            'label' => 'Size W',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('edition', null, array(
            'label' => 'Edition',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('volumes', null, array(
            'label' => 'Volumes',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('pagination', null, array(
            'label' => 'Pagination',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('pricePound', null, array(
            'label' => 'Price Pound',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('priceShilling', null, array(
            'label' => 'Price Shilling',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('pricePence', null, array(
            'label' => 'Price Pence',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('sourceId', null, array(
            'label' => 'Source Id',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('source2Id', null, array(
            'label' => 'Source2 Id',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('shelfmark', null, array(
            'label' => 'Shelfmark',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('checked', ChoiceType::class, array(
            'label' => 'Checked',
            'expanded' => true,
            'multiple' => false,
            'choices' => array(
                'Yes' => true,
                'No' => false,
            ),
            'required' => true,
            'placeholder' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('finalcheck', ChoiceType::class, array(
            'label' => 'Finalcheck',
            'expanded' => true,
            'multiple' => false,
            'choices' => array(
                'Yes' => true,
                'No' => false,
            ),
            'required' => true,
            'placeholder' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('notes', null, array(
            'label' => 'Notes',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
//        $builder->add('locationOfPrinting');
//        $builder->add('format');
//        $builder->add('genre');
//        $builder->add('source');
//        $builder->add('source2');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Title::class
        ));
    }

}
