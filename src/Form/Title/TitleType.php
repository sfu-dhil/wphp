<?php

declare(strict_types=1);

namespace App\Form\Title;

use App\Entity\Currency;
use App\Entity\Format;
use App\Entity\Genre;
use App\Entity\Geonames;
use App\Entity\Title;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Form definition for the title type.
 */
class TitleType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('title', null, [
            'label' => 'Title',
            'required' => true,
            'help' => 'title.form.title',
        ]);
        $builder->add('titleRoles', CollectionType::class, [
            'label' => 'Personal Contributions',
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => TitlePersonType::class,
            'entry_options' => [
                'label' => false,
            ],
            'by_reference' => true,
            'help' => 'title.form.contributors',
            'attr' => [
                'class' => 'collection collection-complex',
            ],
        ]);
        $builder->add('signedAuthor', null, [
            'label' => 'Signed Author',
            'required' => false,
            'help' => 'title.form.signedAuthor',
        ]);
        $builder->add('pseudonym', null, [
            'label' => 'Pseudonym',
            'required' => false,
            'help' => 'title.form.psuedonym',
        ]);
        $builder->add('titleFirmroles', CollectionType::class, [
            'label' => 'Firm Contributions',
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => TitleFirmType::class,
            'entry_options' => [
                'label' => false,
            ],
            'by_reference' => true,
            'help' => 'title.form.titleFirmRoles',
            'attr' => [
                'class' => 'collection collection-complex',
            ],
        ]);
        $builder->add('selfpublished', ChoiceType::class, [
            'label' => 'Self-Published',
            'expanded' => true,
            'multiple' => false,
            'choices' => [
                'Yes' => true,
                'No' => false,
                'Unknown' => null,
            ],
            'required' => false,
            'placeholder' => false,
            'help' => 'title.form.selfPublished',
        ]);
        $builder->add('volumes', null, [
            'label' => 'Volumes',
            'required' => false,
            'help' => 'title.form.volumes',
        ]);
        $builder->add('pagination', null, [
            'label' => 'Pagination',
            'required' => false,
            'help' => 'title.form.pagination',
        ]);
        $builder->add('pubdate', null, [
            'label' => 'Publication Date',
            'required' => false,
            'help' => 'title.form.pubDate',
        ]);
        $builder->add('edition', null, [
            'label' => 'Edition Statement',
            'required' => false,
            'help' => 'title.form.edition',
        ]);
        $builder->add('editionNumber', null, [
            'label' => 'Edition Number',
            'required' => false,
            'help' => 'title.form.editionNumber',
        ]);
        $builder->add('dateOfFirstPublication', null, [
            'label' => 'Date of First Publication',
            'required' => false,
            'help' => 'title.form.dateOfFirstPublication',
        ]);
        $builder->add('imprint', null, [
            'label' => 'Imprint',
            'required' => false,
            'help' => 'title.form.imprint',
        ]);
        $builder->add('colophon', TextareaType::class, [
            'label' => 'Colophon',
            'required' => false,
            'help' => 'title.form.colophon',
        ]);
        $builder->add('copyright', null, [
            'label' => 'Copyright Statement',
            'required' => false,
            'help' => 'title.form.copyright',
        ]);
        $builder->add('locationOfPrinting', Select2EntityType::class, [
            'label' => 'Location of printing',
            'multiple' => false,
            'remote_route' => 'geonames_typeahead',
            'class' => Geonames::class,
            'primary_key' => 'geonameid',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
            'help' => 'title.form.locationOfPrinting',
            'placeholder' => 'Search for an existing location by name',
        ]);
        $builder->add('format', EntityType::class, [
            'label' => 'Format',
            'class' => Format::class,
            'query_builder' => fn (ServiceEntityRepository $repo) => $repo->createQueryBuilder('u')->orderBy('u.name', 'ASC'),
            'choice_label' => fn (Format $format) => "{$format->getName()} ({$format->getAbbreviation()})",
            'multiple' => false,
            'expanded' => false,
            'required' => false,
            'placeholder' => 'Unknown',
            'help' => 'title.form.format',
        ]);
        $builder->add('sizeL', null, [
            'label' => 'Size L',
            'required' => false,
            'help' => 'title.form.sizeL',
        ]);
        $builder->add('sizeW', null, [
            'label' => 'Size W',
            'required' => false,
            'help' => 'title.form.sizeW',
        ]);
        $builder->add('pricePound', NumberType::class, [
            'label' => 'Price Pound',
            'required' => false,
            'help' => 'title.form.pricePound',
        ]);
        $builder->add('priceShilling', NumberType::class, [
            'label' => 'Price Shilling',
            'required' => false,
            'help' => 'title.form.priceShilling',
        ]);
        $builder->add('pricePence', NumberType::class, [
            'label' => 'Price Pence',
            'required' => false,
            'help' => 'title.form.pricePence',
        ]);

        $builder->add('otherPrice', NumberType::class, [
            'label' => 'Non-UK price',
            'scale' => 2,
            'required' => false,
            'help' => 'title.form.otherPrice',
        ]);

        $builder->add('otherPrice', NumberType::class, [
            'label' => 'Non-UK price',
            'scale' => 2,
            'required' => false,
            'help' => 'title.form.otherPrice',
        ]);

        $builder->add('otherCurrency', EntityType::class, [
            'label' => 'Non-UK Currency',
            'class' => Currency::class,
            'choice_label' => 'name',
            'expanded' => false,
            'multiple' => false,
            'required' => false,
            'placeholder' => '',
            'query_builder' => fn (ServiceEntityRepository $er) => $er->createQueryBuilder('e')
                ->orderBy('e.name', 'ASC'),
            'help' => 'title.form.otherCurrency',
        ]);

        $builder->add('genres', EntityType::class, [
            'label' => 'Genres',
            'class' => Genre::class,
            'choice_label' => 'name',
            'expanded' => false,
            'multiple' => true,
            'required' => false,
            'placeholder' => 'Unknown',
            'query_builder' => fn (ServiceEntityRepository $er) => $er->createQueryBuilder('e')
                ->orderBy('e.name', 'ASC'),
            'help' => 'title.form.genre',
        ]);
        $builder->add('shelfmark', null, [
            'label' => 'Shelfmark',
            'required' => false,
            'help' => 'title.form.shelfmark',
        ]);
        $builder->add('titleSources', CollectionType::class, [
            'label' => 'Title Sources',
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => TitleSourceType::class,
            'entry_options' => [
                'label' => false,
            ],
            'by_reference' => false,
            'help' => 'title.form.titleSources',
            'attr' => [
                'class' => 'collection collection-complex',
            ],
        ]);
        $builder->add('relatedTitles', Select2EntityType::class, [
            'label' => 'Related Titles',
            'text_property' => 'getTitleId',
            'multiple' => true,
            'remote_route' => 'title_typeahead',
            'class' => Title::class,
            'allow_clear' => true,
        ]);
        $builder->add('notes', null, [
            'label' => 'Notes',
            'required' => false,
            'help' => 'title.form.notes',
        ]);
        $builder->add('checked', ChoiceType::class, [
            'label' => 'Hand-Verified',
            'expanded' => true,
            'multiple' => false,
            'choices' => [
                'Yes' => true,
                'No' => false,
            ],
            'required' => true,
            'placeholder' => false,
            'help' => 'title.form.checked',
        ]);
        $builder->add('finalcheck', ChoiceType::class, [
            'label' => 'Verified',
            'expanded' => true,
            'multiple' => false,
            'choices' => [
                'Yes' => true,
                'No' => false,
            ],
            'required' => true,
            'placeholder' => false,
            'help' => 'title.form.finalcheck',
        ]);
        $builder->add('finalattempt', ChoiceType::class, [
            'label' => 'Attempted Verification',
            'expanded' => true,
            'multiple' => false,
            'choices' => [
                'Yes' => true,
                'No' => false,
            ],
            'required' => true,
            'placeholder' => false,
            'help' => 'title.form.finalattempt',
        ]);
        $builder->add('editionChecked', ChoiceType::class, [
            'label' => 'Edition Checked',
            'expanded' => true,
            'multiple' => false,
            'choices' => [
                'Yes' => true,
                'No' => false,
            ],
            'required' => true,
            'placeholder' => false,
            'help' => 'title.form.editionChecked',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => Title::class,
        ]);
    }
}
