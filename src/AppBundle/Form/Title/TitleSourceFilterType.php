<?php

namespace AppBundle\Form\Title;

use AppBundle\Entity\Source;
use AppBundle\Entity\TitleSource;
use Doctrine\ORM\EntityRepository;
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
    public function buildForm(FormBuilderInterface $builder, array $options) {
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
            'attr' => [
                'source.search.name'
            ]
        ));
        $builder->add('identifier', null, array(
            'label' => 'Source ID',
            'required' => false,
            'attr' => [
                'source.search.identifier'
            ]
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
