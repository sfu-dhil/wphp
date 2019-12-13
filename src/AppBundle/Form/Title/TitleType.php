<?php

namespace AppBundle\Form\Title;

use AppBundle\Entity\Format;
use AppBundle\Entity\Genre;
use AppBundle\Entity\Geonames;
use AppBundle\Entity\Title;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Form definition for the title type.
 */
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
                'help_block' => 'title.form.title',
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
            'by_reference' => true,
            'attr' => array(
                'class' => 'collection collection-complex',
                'help_block' => 'title.form.contributors',
            ),
        ));
        $builder->add('signedAuthor', null, array(
            'label' => 'Signed Author',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.form.signedAuthor',
            ),
        ));
        $builder->add('pseudonym', null, array(
            'label' => 'Pseudonym',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.form.psuedonym',
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
                'help_block' => 'title.form.titleFirmRoles',
            ),
        ));
        $builder->add('selfpublished', ChoiceType::class, array(
            'label' => 'Self-Published',
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
                'help_block' => 'title.form.selfPublished',
            ),
        ));
        $builder->add('volumes', null, array(
            'label' => 'Volumes',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.form.volumes',
            ),
        ));
        $builder->add('pagination', null, array(
            'label' => 'Pagination',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.form.pagination',
            ),
        ));
        $builder->add('pubdate', null, array(
            'label' => 'Publication Date',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.form.pubDate',
            ),
        ));
        $builder->add('edition', null, array(
            'label' => 'Edition',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.form.edition',
            ),
        ));
        $builder->add('editionNumber', null, array(
            'label' => 'Edition Number',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.form.editionNumber',
            ),
        ));
        $builder->add('dateOfFirstPublication', null, array(
            'label' => 'Date of First Publication',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.form.dateOfFirstPublication',
            ),
        ));
        $builder->add('imprint', null, array(
            'label' => 'Imprint',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.form.imprint',
            ),
        ));
        $builder->add('colophon', null, array(
            'label' => 'Colophon',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.form.colophon',
            ),
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
                'help_block' => 'title.form.locationOfPrinting',
            ),
        ));
        $builder->add('format', EntityType::class, array(
            'class' => Format::class,
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('u')->orderBy('u.name', 'ASC');
            },
            'choice_label' => function (Format $format) {
                return "{$format->getName()} ({$format->getAbbreviation()})";
            },
            'multiple' => false,
            'expanded' => false,
            'required' => false,
            'placeholder' => 'Unknown',
            'attr' => array(
                'help_block' => 'title.form.format',
            ),
        ));
        $builder->add('sizeL', null, array(
            'label' => 'Size L',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.form.sizeL',
            ),
        ));
        $builder->add('sizeW', null, array(
            'label' => 'Size W',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.form.sizeW',
            ),
        ));
        $builder->add('pricePound', null, array(
            'label' => 'Price Pound',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.form.pricePound',
            ),
        ));
        $builder->add('priceShilling', null, array(
            'label' => 'Price Shilling',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.form.priceShilling',
            ),
        ));
        $builder->add('pricePence', null, array(
            'label' => 'Price Pence',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.form.pricePence',
            ),
        ));
        $builder->add('genre', EntityType::class, array(
            'class' => Genre::class,
            'choice_label' => 'name',
            'expanded' => false,
            'multiple' => false,
            'required' => false,
            'placeholder' => 'Unknown',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('e')
                    ->orderBy('e.name', 'ASC')
                ;
            },
            'attr' => array(
                'help_block' => 'title.form.genre',
            ),
        ));
        $builder->add('shelfmark', null, array(
            'label' => 'Shelfmark',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.form.shelfmark',
            ),
        ));
        $builder->add('titleSources', CollectionType::class, array(
            'label' => 'Title Sources',
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => TitleSourceType::class,
            'entry_options' => array(
                'label' => false,
            ),
            'by_reference' => false,
            'attr' => array(
                'class' => 'collection collection-complex',
                'help_block' => 'title.form.titleSources',
            ),
        ));
        $builder->add('notes', null, array(
            'label' => 'Notes',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.form.notes',
            ),
        ));
        $builder->add('checked', ChoiceType::class, array(
            'label' => 'Hand-Verified',
            'expanded' => true,
            'multiple' => false,
            'choices' => array(
                'Yes' => true,
                'No' => false,
            ),
            'required' => true,
            'placeholder' => false,
            'attr' => array(
                'help_block' => 'title.form.checked',
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
                'help_block' => 'title.form.finalcheck',
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
                'help_block' => 'title.form.finalattempt',
            ),
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Title::class,
        ));
    }
}
