<?php

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
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('source', EntityType::class, array(
            'class' => Source::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('ts')->orderBy('ts.name', 'ASC');
            },
            'label' => 'Source',
            'choice_label' => 'name',
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'placeholder' => 'Select a source to filter the results.',
            'attr' => array(
                'source.search.name',
            ),
        ));
        $builder->add('identifier', null, array(
            'label' => 'Source ID',
            'required' => false,
            'attr' => array(
                'source.search.identifier',
            ),
        ));
    }

    /**
     * {@inheritdoc}
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => TitleSource::class,
        ));
    }
}
