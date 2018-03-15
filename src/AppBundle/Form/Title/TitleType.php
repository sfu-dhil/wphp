<?php

namespace AppBundle\Form\Title;

use AppBundle\Entity\Format;
use AppBundle\Entity\Genre;
use AppBundle\Entity\Geonames;
use AppBundle\Entity\Source;
use AppBundle\Entity\Title;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

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
        $builder->add('titleRoles', CollectionType::class, array(
            'label' => 'Personal Contributions',
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => TitlePersonType::class,
            'entry_options' => array(
                'label' => false,
            ),
            'by_reference' => false,
            'attr' => array(
                'class' => 'collection collection-complex'
            ),
        ));
        $builder->add('titleFirmroles', CollectionType::class, array(
            'label' => 'Firm Contributions',
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => TitleFirmType::class,
            'entry_options' => array(
                'label' => false,
            ),
            'attr' => array(
                'class' => 'collection collection-complex'
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
        $builder->add('genre', Select2EntityType::class, array(
            'multiple' => false,
            'remote_route' => 'genre_typeahead',
            'class' => Genre::class,
            'primary_key' => 'id',
            'text_property' => 'name',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',            
        ));
        
        $builder->add('locationOfPrinting', Select2EntityType::class, array(
            'multiple' => false,
            'remote_route' => 'geonames_typeahead',
            'class' => Geonames::class,
            'primary_key' => 'geonameid',
            'text_property' => 'name',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',            
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
        $builder->add('format', Select2EntityType::class, array(
            'multiple' => false,
            'remote_route' => 'format_typeahead',
            'class' => Format::class,
            'primary_key' => 'id',
            'text_property' => 'name',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',            
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
        $builder->add('source', Select2EntityType::class, array(
            'multiple' => false,
            'remote_route' => 'source_typeahead',
            'class' => Source::class,
            'primary_key' => 'id',
            'text_property' => 'name',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',            
        ));
        
        $builder->add('sourceId', null, array(
            'label' => 'Source1 Id',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('source2', Select2EntityType::class, array(
            'multiple' => false,
            'remote_route' => 'source_typeahead',
            'class' => Source::class,
            'primary_key' => 'id',
            'text_property' => 'name',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',            
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
