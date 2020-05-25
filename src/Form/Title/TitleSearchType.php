<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

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
    /**
     * @var FormatRepository
     */
    private $formatRepository;

    /**
     * @var GenreRepository
     */
    private $genreRepository;

    public function __construct(FormatRepository $formatRepository, GenreRepository $genreRepository) {
        $this->formatRepository = $formatRepository;
        $this->genreRepository = $genreRepository;
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
            'attr' => [
                'help_block' => 'title.search.title',
            ],
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
            'attr' => [
                'help_block' => 'title.search.sort',
            ],
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'empty_data' => null,
            'data' => null,
        ]);

        $builder->add('id', TextType::class, [
            'label' => 'Title ID',
            'required' => false,
            'attr' => [
                'help_block' => 'title.search.id',
            ],
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
            'attr' => [
                'help_block' => 'title.search.signedAuthor',
            ],
        ]);
        $builder->add('pseudonym', TextType::class, [
            'label' => 'Pseudonym',
            'required' => false,
            'attr' => [
                'help_block' => 'title.search.psuedonym',
            ],
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
            'attr' => [
                'help_block' => 'title.search.selfPublished',
            ],
            'required' => false,
            'expanded' => true,
            'multiple' => false,
            'data' => null,
        ]);

        $builder->add('volumes', TextType::class, [
            'label' => 'Volumes',
            'required' => false,
            'attr' => [
                'help_block' => 'title.search.volumes',
            ],
        ]);
        $builder->add('pubdate', TextType::class, [
            'label' => 'Date of Publication',
            'required' => false,
            'attr' => [
                'help_block' => 'title.search.pubDate',
            ],
        ]);
        $builder->add('date_of_first_publication', TextType::class, [
            'label' => 'Date of First Publication',
            'required' => false,
            'attr' => [
                'help_block' => 'title.search.dateOfFirstPublication',
            ],
        ]);
        $builder->add('editionNumber', TextType::class, [
            'label' => 'Edition Number',
            'required' => false,
            'attr' => [
                'help_block' => 'title.search.editionNumber',
            ],
        ]);
        $builder->add('imprint', TextType::class, [
            'label' => 'Imprint',
            'required' => false,
            'attr' => [
                'help_block' => 'title.search.imprint',
            ],
        ]);
        $builder->add('colophon', null, [
            'label' => 'Colophon',
            'required' => false,
            'attr' => [
                'help_block' => 'title.search.colophon',
            ],
        ]);
        $builder->add('location', TextType::class, [
            'label' => 'Location of Printing',
            'required' => false,
            'attr' => [
                'help_block' => 'title.search.locationOfPrinting',
            ],
        ]);
        $builder->add('format', ChoiceType::class, [
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
            'attr' => [
                'help_block' => 'title.search.format',
            ],
        ]);
        $builder->add('sizeL', TextType::class, [
            'label' => 'Length',
            'required' => false,
            'attr' => [
                'help_block' => 'title.search.sizeL',
            ],
        ]);
        $builder->add('sizeW', TextType::class, [
            'label' => 'Width',
            'required' => false,
            'attr' => [
                'help_block' => 'title.search.sizeW',
            ],
        ]);
        $builder->add('price_filter', PriceType::class, [
            'label' => 'Filter by Price',
            'required' => false,
            'attr' => [
                'class' => 'embedded-form',
            ],
        ]);

        $builder->add('genre', ChoiceType::class, [
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
            'attr' => [
                'help_block' => 'title.search.genre',
            ],
        ]);
        $builder->add('shelfmark', null, [
            'label' => 'Shelfmark',
            'required' => false,
            'attr' => [
                'help_block' => 'title.search.shelfmark',
            ],
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
            'attr' => [
                'help_block' => 'title.search.notes',
            ],
        ]);

        if ($user) {
            $builder->add('checked', ChoiceType::class, [
                'label' => 'Hand-Verified',
                'choices' => [
                    'Yes' => 'Y',
                    'No' => 'N',
                ],
                'attr' => [
                    'help_block' => 'title.search.checked',
                ],
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
                'attr' => [
                    'help_block' => 'title.search.finalcheck',
                ],
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
                'attr' => [
                    'help_block' => 'title.search.finalattempt',
                ],
                'required' => false,
                'expanded' => true,
                'multiple' => false,
                'empty_data' => null,
                'data' => null,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) : void {
        parent::configureOptions($resolver);
        $resolver->setRequired(['user']);
    }
}
