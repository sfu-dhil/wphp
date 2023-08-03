<?php

declare(strict_types=1);

namespace App\Form\Title;

use App\Entity\Source;
use App\Entity\TitleSource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Search form for person entities.
 */
class TitleSourceFilterType extends AbstractType {
    /**
     * Build the form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('source', EntityType::class, [
            'label' => 'Source',
            'class' => Source::class,
            'query_builder' => fn (ServiceEntityRepository $er) => $er->createQueryBuilder('ts')->orderBy('ts.name', 'ASC'),
            'choice_label' => 'name',
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'placeholder' => 'Select a source to filter the results.',
            'attr' => [
                'source.search.name',
            ],
        ]);
        $builder->add('identifier', null, [
            'label' => 'Source ID',
            'required' => false,
            'attr' => [
                'source.search.identifier',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => TitleSource::class,
        ]);
    }
}
