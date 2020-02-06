<?php

namespace App\Form\Title;

use App\Entity\Format;
use App\Entity\Genre;
use App\Form\Firm\FirmFilterType;
use App\Form\Person\PersonFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Search form for titles.
 */
class TitleSearchType extends AbstractType {
    /**
     * Build the form.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->setMethod('get');
        $em = $options['entity_manager'];
        $user = $options['user'];

        $formats = $em->getRepository(Format::class)->findAll(array(
            'name' => 'ASC',
        ));
        $genres = $em->getRepository(Genre::class)->findAll(array(
            'name' => 'ASC',
        ));

        $builder->add('title', TextType::class, array(
            'label' => 'Search Titles',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.search.title',
            ),
        ));

        $builder->add('order', ChoiceType::class, array(
            'label' => 'Results Sorted By',
            'choices' => array(
                'Title (A to Z)' => 'title_asc',
                'Title (Z to A)' => 'title_desc',
                'Publication Date (Oldest to Newest)' => 'pubdate_asc',
                'Publication Date (Newest to Oldest)' => 'pubdate_desc',
                'First Publication Date (Oldest to Newest)' => 'first_pubdate_asc',
                'First Publication Date (Newest to Oldest)' => 'first_pubdate_desc',
                'Edition Number (Lowest to Highest)' => 'edition_asc',
                'Edition Number (Highest to Lowest)' => 'edition_desc',
            ),
            'attr' => array(
                'help_block' => 'Choose a sort method for the results',
            ),
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'empty_data' => null,
            'data' => null,
        ));

        $builder->add('id', TextType::class, array(
            'label' => 'Title ID',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.search.id',
            ),
        ));
        $builder->add('person_filter', PersonFilterType::class, array(
            'label' => 'Filter by Person',
            'required' => false,
            'attr' => array(
                'class' => 'embedded-form',
            ),
        ));
        $builder->add('signed_author', TextType::class, array(
            'label' => 'Signed Author',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.search.signedAuthor',
            ),
        ));
        $builder->add('pseudonym', TextType::class, array(
            'label' => 'Pseudonym',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.search.psuedonym',
            ),
        ));
        $builder->add('firm_filter', FirmFilterType::class, array(
            'label' => 'Filter by Firm',
            'required' => false,
            'attr' => array(
                'class' => 'embedded-form',
            ),
        ));

        $builder->add('self_published', ChoiceType::class, array(
            'label' => 'Self-Published',
            'choices' => array(
                'Yes' => 'Y',
                'No' => 'N',
                'Unknown' => 'U',
            ),
            'attr' => array(
                'help_block' => 'title.search.selfPublished',
            ),
            'required' => false,
            'expanded' => true,
            'multiple' => false,
            'empty_data' => null,
            'data' => null,
        ));

        $builder->add('volumes', TextType::class, array(
            'label' => 'Volumes',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.search.volumes',
            ),
        ));
        $builder->add('pubdate', TextType::class, array(
            'label' => 'Date of Publication',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.search.pubDate',
            ),
        ));
        $builder->add('date_of_first_publication', TextType::class, array(
            'label' => 'Date of First Publication',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.search.dateOfFirstPublication',
            ),
        ));
        $builder->add('editionNumber', TextType::class, array(
            'label' => 'Edition Number',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.search.editionNumber',
            ),
        ));
        $builder->add('imprint', TextType::class, array(
            'label' => 'Imprint',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.search.imprint',
            ),
        ));
        $builder->add('colophon', null, array(
            'label' => 'Colophon',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.search.colophon',
            ),
        ));
        $builder->add('location', TextType::class, array(
            'label' => 'Location of Printing',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.search.locationOfPrinting',
            ),
        ));
        $builder->add('format', ChoiceType::class, array(
            'choices' => $formats,
            'choice_label' => function ($value, $key, $index) {
                return $value->getName();
            },
            'choice_value' => function ($value) {
                return $value->getId();
            },
            'label' => 'Format',
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'attr' => array(
                'help_block' => 'title.search.format',
            ),
        ));
        $builder->add('sizeL', TextType::class, array(
            'label' => 'Length',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.search.sizeL',
            ),
        ));
        $builder->add('sizeW', TextType::class, array(
            'label' => 'Width',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.search.sizeW',
            ),
        ));
        $builder->add('price_filter', PriceType::class, array(
            'label' => 'Filter by Price',
            'required' => false,
            'attr' => array(
                'class' => 'embedded-form',
            ),
        ));

        $builder->add('genre', ChoiceType::class, array(
            'choices' => $genres,
            'choice_label' => function ($value, $key, $index) {
                return $value->getName();
            },
            'choice_value' => function ($value) {
                return $value->getId();
            },
            'label' => 'Genre',
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'attr' => array(
                'help_block' => 'title.search.genre',
            ),
        ));
        $builder->add('shelfmark', null, array(
            'label' => 'Shelfmark',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.search.shelfmark',
            ),
        ));
        $builder->add('titlesource_filter', TitleSourceFilterType::class, array(
            'label' => 'Filter by Source',
            'required' => false,
            'attr' => array(
                'class' => 'embedded-form',
            ),
        ));

        $builder->add('notes', null, array(
            'label' => 'Notes',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.search.notes',
            ),
        ));

        if ($user) {
            $builder->add('checked', ChoiceType::class, array(
                'label' => 'Hand-Verified',
                'choices' => array(
                    'Yes' => 'Y',
                    'No' => 'N',
                ),
                'attr' => array(
                    'help_block' => 'title.search.checked',
                ),
                'required' => false,
                'expanded' => true,
                'multiple' => false,
                'empty_data' => null,
                'data' => null,
            ));

            $builder->add('finalcheck', ChoiceType::class, array(
                'label' => 'Verified',
                'choices' => array(
                    'Yes' => 'Y',
                    'No' => 'N',
                ),
                'attr' => array(
                    'help_block' => 'title.search.finalcheck',
                ),
                'required' => false,
                'expanded' => true,
                'multiple' => false,
                'empty_data' => null,
                'data' => null,
            ));

            $builder->add('finalattempt', ChoiceType::class, array(
                'label' => 'Attempted Verification',
                'choices' => array(
                    'Yes' => 'Y',
                    'No' => 'N',
                ),
                'attr' => array(
                    'help_block' => 'title.search.finalattempt',
                ),
                'required' => false,
                'expanded' => true,
                'multiple' => false,
                'empty_data' => null,
                'data' => null,
            ));
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        parent::configureOptions($resolver);
        $resolver->setRequired(array('entity_manager', 'user'));
    }
}
