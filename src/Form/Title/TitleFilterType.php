<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form\Title;

use App\Entity\Genre;
use App\Entity\Role;
use Doctrine\ORM\EntityManager;
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
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    /**
     * Build the form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $genreRepo = $this->em->getRepository(Genre::class);
        $roleRepo = $this->em->getRepository(Role::class);
        $roles = $roleRepo->findAll([
            'name' => 'ASC',
        ]);
        $genres = $genreRepo->findAll([
            'name' => 'asc',
        ]);

        $builder->add('title', TextType::class, [
            'label' => 'Title',
            'required' => false,
            'attr' => [
                'help_block' => 'title.search.title',
            ],
        ]);

        $builder->add('person_role', ChoiceType::class, [
            'choices' => $roles,
            'choice_label' => function ($value, $key, $index) {
                return $value->getName();
            },
            'choice_value' => function ($value) {
                if ($value) {
                    return $value->getId();
                }
            },
            'label' => 'Person Role',
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'attr' => [
                'help_block' => 'title.search.titleRoles',
            ],
        ]);

        $builder->add('pubdate', TextType::class, [
            'label' => 'Date of Publication',
            'required' => false,
            'attr' => [
                'help_block' => 'title.search.pubdate',
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

        $builder->add('location', TextType::class, [
            'label' => 'Location of Printing',
            'required' => false,
            'attr' => [
                'help_block' => 'title.search.locationOfPrinting',
            ],
        ]);
    }
}
