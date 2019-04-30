<?php

namespace AppBundle\Form\Title;

use AppBundle\Entity\Format;
use AppBundle\Entity\Genre;
use AppBundle\Entity\Geonames;
use AppBundle\Entity\Source;
use AppBundle\Entity\Title;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
                'help_block' => 'Full title, subtitle, signed author, and edition statement from the title page',
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
                'class' => 'collection collection-complex',
                'help_block' => 'Names of all women who have contributed to the work and their role in the work’s production',
            ),
        ));
        $builder->add('signedAuthor', null, array(
            'label' => 'Signed Author',
            'required' => false,
            'attr' => array(
                'help_block' => 'Author attribution from the title page or the end of the preface',
            ),
        ));
        $builder->add('pseudonym', null, array(
            'label' => 'Pseudonym',
            'required' => false,
            'attr' => array(
                'help_block' => 'Any false author name',
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
            'by_reference' => true,
            'attr' => array(
                'class' => 'collection collection-complex',
                'help_block' => 'Names of all printers, publishers, and booksellers listed in the imprint and their role in the work’s production',
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
                'help_block' => 'Indicates if the title was published by the author',
            ),
        ));
        $builder->add('volumes', null, array(
            'label' => 'Volumes',
            'required' => false,
            'attr' => array(
                'help_block' => 'Number of volumes of the edition, using arabic numerals',
            ),
        ));
        $builder->add('pagination', null, array(
            'label' => 'Pagination',
            'required' => false,
            'attr' => array(
                'help_block' => 'Number of pages of each volume',
            ),
        ));
        $builder->add('pubdate', null, array(
            'label' => 'Pubdate',
            'required' => false,
            'attr' => array(
                'help_block' => 'Date (year) as it appears in the imprint',
            ),
        ));
        $builder->add('edition', null, array(
            'label' => 'Edition',
            'required' => false,
            'attr' => array(
                'help_block' => 'Edition as it appears on the title page',
            ),
        ));
        $builder->add('editionNumber', null, array(
            'label' => 'Edition Number',
            'required' => false,
            'attr' => array(
                'help_block' => 'Numerical form of the edition',
            ),
        ));
        $builder->add('dateOfFirstPublication', null, array(
            'label' => 'Date Of First Publication',
            'required' => false,
            'attr' => array(
                'help_block' => 'Date (year) that the work was first published',
            ),
        ));
        $builder->add('imprint', null, array(
            'label' => 'Imprint',
            'required' => false,
            'attr' => array(
                'help_block' => 'Information about printers, publishers, booksellers as shown on the title page',
            ),
        ));
        $builder->add('colophon', null, array(
            'label' => 'Colophon',
            'required' => false,
        ));

        $builder->add('locationOfPrinting', Select2EntityType::class, array(
            'multiple' => false,
            'remote_route' => 'geonames_typeahead',
            'class' => Geonames::class,
            'primary_key' => 'geonameid',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
            'attr' => array(
                'help_block' => 'Geotagged location as indicated by the imprint'
            ),
        ));
        $builder->add('format', EntityType::class, array(
            'class' => Format::class,
            'query_builder' => function(EntityRepository $repo) {
                return $repo->createQueryBuilder('u')->orderBy('u.name', 'ASC');
            },
            'choice_label' => function(Format $format) {
                return "{$format->getName()} ({$format->getAbbrevTwo()})";
            },
            'multiple' => false,
            'expanded' => false,
        ));
        $builder->add('sizeL', null, array(
            'label' => 'Size L',
            'required' => false,
            'attr' => array(
                'help_block' => 'Length measured in cm',
            ),
        ));
        $builder->add('sizeW', null, array(
            'label' => 'Size W',
            'required' => false,
            'attr' => array(
                'help_block' => 'Width measured in cm',
            ),
        ));
        $builder->add('pricePound', null, array(
            'label' => 'Price Pound',
            'required' => false,
            'attr' => array(
                'help_block' => 'Portion of the price of the work in pounds',
            ),
        ));
        $builder->add('priceShilling', null, array(
            'label' => 'Price Shilling',
            'required' => false,
            'attr' => array(
                'help_block' => 'Portion of the price of the work in shillings',
            ),
        ));
        $builder->add('pricePence', null, array(
            'label' => 'Price Pence',
            'required' => false,
            'attr' => array(
                'help_block' => 'Portion of the price of the work in pence',
            ),
        ));
        $builder->add('genre', EntityType::class, array(
            'class' => Genre::class,
            'choice_label' => 'name',
            'expanded' => false,
            'multiple' => false,
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('e')
                    ->orderBy('e.name', 'ASC');
            },
            'attr' => array(
                'help_block' => 'Category of the work',
            ),
        ));
        $builder->add('shelfmark', null, array(
            'label' => 'Shelfmark',
            'required' => false,
            'attr' => array(
                'help_block' => 'Call numbers for location in various libraries',
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
            'attr' => array(
                'help_block' => 'First source consulted to populate the entry fields',
            ),
        ));

        $builder->add('sourceId', null, array(
            'label' => 'Source1 Id',
            'required' => false,
            'attr' => array(
                'help_block' => 'Unique numeric source identifier if available',
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
            'attr' => array(
                'help_block' => 'Second source consulted to populate the entry fields',
            ),
        ));
        $builder->add('source2Id', null, array(
            'label' => 'Source2 Id',
            'required' => false,
            'attr' => array(
                'help_block' => 'Unique numeric source identifier if available',
            ),
        ));
        $builder->add('source3', Select2EntityType::class, array(
            'multiple' => false,
            'remote_route' => 'source_typeahead',
            'class' => Source::class,
            'primary_key' => 'id',
            'text_property' => 'name',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
            'attr' => array(
                'help_block' => 'Second source consulted to populate the entry fields',
            ),
        ));
        $builder->add('source3Id', null, array(
            'label' => 'Source3 Id',
            'required' => false,
            'attr' => array(
                'help_block' => 'Unique numeric source identifier if available',
            ),
        ));
        $builder->add('notes', null, array(
            'label' => 'Notes',
            'required' => false,
            'attr' => array(
                'help_block' => 'Any other important information, including links to sources',
            ),
        ));
        $builder->add('checked', ChoiceType::class, array(
            'label' => 'Hand-verified',
            'expanded' => true,
            'multiple' => false,
            'choices' => array(
                'Yes' => true,
                'No' => false,
            ),
            'required' => true,
            'placeholder' => false,
            'attr' => array(
                'help_block' => 'Indicates that a physical copy of the text has been consulted',
            ),
        ));
        $builder->add('finalcheck', ChoiceType::class, array(
            'label' => 'Verified',
            'expanded' => true,
            'multiple' => false,
            'choices' => array(
                'Yes' => true,
                'No' => false,
            ),
            'required' => true,
            'placeholder' => false,
            'attr' => array(
                'help_block' => 'Indicates that either two sources have been consulted or the text has been hand-checked',
            ),
        ));
        $builder->add('finalattempt', ChoiceType::class, array(
            'label' => 'Attempted Verification',
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
