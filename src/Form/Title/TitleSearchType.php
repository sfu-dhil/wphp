<?php

declare(strict_types=1);

namespace App\Form\Title;

use App\Form\Firm\FirmFilterType;
use App\Form\Person\PersonFilterType;
use App\Repository\FormatRepository;
use App\Repository\GenreRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Search form for titles.
 */
class TitleSearchType extends AbstractType {
    public function __construct(private FormatRepository $formatRepository, private GenreRepository $genreRepository) {
    }

    /**
     * Build the form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->setMethod('get');
        $user = $options['user'];

        $formats = $this->formatRepository->findBy([], [
            'name' => 'ASC',
        ]);
        $genres = $this->genreRepository->findBy([], [
            'name' => 'ASC',
        ]);

        $builder->add('title', TextType::class, [
            'label' => 'Search Titles',
            'required' => false,
            'help' => 'title.search.title',
        ]);

        $builder->add('order', ChoiceType::class, [
            'label' => 'Results Sorted By',
            'choices' => [
                'Title (A to Z)' => 'title_asc',
                'Title (Z to A)' => 'title_desc',
                'Publication Date (Oldest to Newest)' => 'pubdate_asc',
                'Publication Date (Newest to Oldest)' => 'pubdate_desc',
                'First Publication Date (Oldest to Newest)' => 'first_pubdate_asc',
                'First Publication Date (Newest to Oldest)' => 'first_pubdate_desc',
                'Edition Number (Lowest to Highest)' => 'edition_asc',
                'Edition Number (Highest to Lowest)' => 'edition_desc',
            ],
            'help' => 'title.search.sort',
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'empty_data' => null,
            'data' => null,
        ]);

        $builder->add('id', TextType::class, [
            'label' => 'Title ID',
            'required' => false,
            'help' => 'title.search.id',
        ]);
        $builder->add('person_filter', PersonFilterType::class, [
            'label' => 'Filter by Person',
            'required' => false,
            'attr' => [
                'class' => 'embedded-form',
            ],
        ]);
        $builder->add('signed_author', TextType::class, [
            'label' => 'Signed Author',
            'required' => false,
            'help' => 'title.search.signedAuthor',
        ]);
        $builder->add('pseudonym', TextType::class, [
            'label' => 'Pseudonym',
            'required' => false,
            'help' => 'title.search.psuedonym',
        ]);
        $builder->add('firm_filter', FirmFilterType::class, [
            'label' => 'Filter by Firm',
            'required' => false,
            'attr' => [
                'class' => 'embedded-form',
            ],
        ]);

        $builder->add('self_published', ChoiceType::class, [
            'label' => 'Self-Published',
            'choices' => [
                'All' => '',
                'Only self-published' => 'Y',
            ],
            'label_attr' => [
                'class' => 'radio-inline',
            ],
            'help' => 'title.search.selfPublished',
            'required' => false,
            'expanded' => true,
            'multiple' => false,
            'data' => null,
        ]);

        $builder->add('volumes', TextType::class, [
            'label' => 'Volumes',
            'required' => false,
            'help' => 'title.search.volumes',
        ]);
        $builder->add('pubdate', TextType::class, [
            'label' => 'Date of Publication',
            'required' => false,
            'help' => 'title.search.pubDate',
        ]);
        $builder->add('date_of_first_publication', TextType::class, [
            'label' => 'Date of First Publication',
            'required' => false,
            'help' => 'title.search.dateOfFirstPublication',
        ]);
        $builder->add('editionNumber', TextType::class, [
            'label' => 'Edition Number',
            'required' => false,
            'help' => 'title.search.editionNumber',
        ]);
        $builder->add('editionStatement', TextType::class, [
            'label' => 'Edition Statement',
            'required' => false,
            'help' => 'title.search.editionStatement',
        ]);
        $builder->add('imprint', TextType::class, [
            'label' => 'Imprint',
            'required' => false,
            'help' => 'title.search.imprint',
        ]);
        $builder->add('colophon', null, [
            'label' => 'Colophon',
            'required' => false,
            'help' => 'title.search.colophon',
        ]);
        $builder->add('copyright', null, [
            'label' => 'Copyright Statement',
            'required' => false,
            'help' => 'title.search.copyright',
        ]);
        $builder->add('location', TextType::class, [
            'label' => 'Location of Printing',
            'required' => false,
            'help' => 'title.search.locationOfPrinting',
        ]);
        $builder->add('format', ChoiceType::class, [
            'label' => 'Format',
            'choices' => $formats,
            'choice_label' => fn ($value, $key, $index) => $value->getName(),
            'choice_value' => fn ($value) => $value->getId(),
            'label_attr' => [
                'class' => 'checkbox-inline',
            ],
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'help' => 'title.search.format',
        ]);
        $builder->add('sizeL', TextType::class, [
            'label' => 'Length',
            'required' => false,
            'help' => 'title.search.sizeL',
        ]);
        $builder->add('sizeW', TextType::class, [
            'label' => 'Width',
            'required' => false,
            'help' => 'title.search.sizeW',
        ]);
        $builder->add('price_filter', PriceType::class, [
            'label' => 'Filter by Price',
            'required' => false,
            'attr' => [
                'class' => 'embedded-form',
            ],
        ]);

        $builder->add('genre', ChoiceType::class, [
            'label' => 'Genre',
            'choices' => $genres,
            'choice_label' => fn ($value, $key, $index) => $value->getName(),
            'choice_value' => fn ($value) => $value ? $value->getid() : null,
            'label_attr' => [
                'class' => 'checkbox-inline',
            ],
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'help' => 'title.search.genre',
        ]);
        $builder->add('shelfmark', null, [
            'label' => 'Shelfmark',
            'required' => false,
            'help' => 'title.search.shelfmark',
        ]);
        $builder->add('titlesource_filter', TitleSourceFilterType::class, [
            'label' => 'Filter by Source',
            'required' => false,
            'attr' => [
                'class' => 'embedded-form',
            ],
        ]);

        $builder->add('notes', null, [
            'label' => 'Notes',
            'required' => false,
            'help' => 'title.search.notes',
        ]);

        if ($user) {
            $builder->add('checked', ChoiceType::class, [
                'label' => 'Hand-Verified',
                'choices' => [
                    'Yes' => 'Y',
                    'No' => 'N',
                ],
                'label_attr' => [
                    'class' => 'radio-inline',
                ],
                'help' => 'title.search.checked',
                'required' => false,
                'expanded' => true,
                'multiple' => false,
                'empty_data' => null,
                'data' => null,
            ]);

            $builder->add('finalcheck', ChoiceType::class, [
                'label' => 'Verified',
                'choices' => [
                    'Yes' => 'Y',
                    'No' => 'N',
                ],
                'label_attr' => [
                    'class' => 'radio-inline',
                ],
                'help' => 'title.search.finalcheck',
                'required' => false,
                'expanded' => true,
                'multiple' => false,
                'empty_data' => null,
                'data' => null,
            ]);

            $builder->add('finalattempt', ChoiceType::class, [
                'label' => 'Attempted Verification',
                'choices' => [
                    'Yes' => 'Y',
                    'No' => 'N',
                ],
                'label_attr' => [
                    'class' => 'radio-inline',
                ],
                'help' => 'title.search.finalattempt',
                'required' => false,
                'expanded' => true,
                'multiple' => false,
                'empty_data' => null,
                'data' => null,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        parent::configureOptions($resolver);
        $resolver->setRequired(['user']);
    }
}
