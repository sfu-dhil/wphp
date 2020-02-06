<?php

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

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    /**
     * Build the form.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $genreRepo = $this->em->getRepository(Genre::class);
        $roleRepo = $this->em->getRepository(Role::class);
        $roles = $roleRepo->findAll(array(
            'name' => 'ASC',
        ));
        $genres = $genreRepo->findAll(array(
            'name' => 'asc',
        ));

        $builder->add('title', TextType::class, array(
            'label' => 'Title',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.search.title',
            ),
        ));

        $builder->add('person_role', ChoiceType::class, array(
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
            'attr' => array(
                'help_block' => 'title.search.titleRoles',
            ),
        ));

        $builder->add('pubdate', TextType::class, array(
            'label' => 'Date of Publication',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.search.pubdate',
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

        $builder->add('location', TextType::class, array(
            'label' => 'Location of Printing',
            'required' => false,
            'attr' => array(
                'help_block' => 'title.search.locationOfPrinting',
            ),
        ));
    }
}
