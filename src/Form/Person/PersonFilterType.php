<?php

declare(strict_types=1);

namespace App\Form\Person;

use App\Entity\Person;
use App\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * PersonFilterType is a subform used in the title search form.
 */
class PersonFilterType extends AbstractType {
    public function __construct(private EntityManagerInterface $em) {
    }

    /**
     * Build the form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $roleRepo = $this->em->getRepository(Role::class);
        $roles = $roleRepo->findBy([], [
            'name' => 'ASC',
        ]);

        $builder->add('id', TextType::class, [
            'label' => 'ID',
            'required' => false,
            'help' => 'person.search.id',
        ]);

        $builder->add('name', TextType::class, [
            'label' => 'Name',
            'required' => false,
            'help' => 'person.search.name',
        ]);

        $builder->add('gender', ChoiceType::class, [
            'label' => 'Gender',
            'choices' => [
                'Female' => Person::FEMALE,
                'Male' => Person::MALE,
                'Transgender' => Person::TRANS,
                'Unknown' => Person::UNKNOWN,
            ],
            'label_attr' => [
                'class' => 'checkbox-inline',
            ],
            'help' => 'person.search.gender',
            'required' => false,
            'expanded' => true,
            'multiple' => true,
        ]);

        $builder->add('person_role', ChoiceType::class, [
            'label' => 'Role',
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
            'help' => 'person.search.role',
        ]);
    }
}
