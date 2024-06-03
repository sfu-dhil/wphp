<?php

declare(strict_types=1);

namespace App\Form\Title;

use App\Entity\Genre;
use App\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Form to filter search results by title. The form is registered as a symfony
 * service to have the doctrine registry injected as a dependency.
 */
class TitleFilterType extends AbstractType {
    public function __construct(private EntityManagerInterface $em) {}

    /**
     * Build the form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $genreRepo = $this->em->getRepository(Genre::class);
        $roleRepo = $this->em->getRepository(Role::class);
        $roles = $roleRepo->findBy([], [
            'name' => 'ASC',
        ]);
        $genres = $genreRepo->findBy([], [
            'name' => 'asc',
        ]);

        $builder->add('id', TextType::class, [
            'label' => 'ID',
            'required' => false,
            'help' => 'title.search.id',
        ]);

        $builder->add('title', TextType::class, [
            'label' => 'Title',
            'required' => false,
            'help' => 'title.search.title',
        ]);

        $builder->add('person_role', ChoiceType::class, [
            'label' => 'Person Role',
            'choices' => $roles,
            'choice_label' => fn ($value, $key, $index) => $value->getName(),
            'choice_value' => function ($value) {
                if ($value) {
                    return $value->getId();
                }
            },
            'label_attr' => [
                'class' => 'checkbox-inline',
            ],
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'help' => 'title.search.titleRoles',
        ]);

        $builder->add('pubdate', TextType::class, [
            'label' => 'Date of Publication',
            'required' => false,
            'help' => 'title.search.pubDate',
        ]);

        $builder->add('genre', ChoiceType::class, [
            'label' => 'Genre',
            'choices' => $genres,
            'choice_label' => fn ($value, $key, $index) => $value->getName(),
            'choice_value' => fn ($value) => $value->getId(),
            'label_attr' => [
                'class' => 'checkbox-inline',
            ],
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'help' => 'title.search.genre',
        ]);

        $builder->add('location', TextType::class, [
            'label' => 'Location of Printing',
            'required' => false,
            'help' => 'title.search.locationOfPrinting',
        ]);
    }
}
