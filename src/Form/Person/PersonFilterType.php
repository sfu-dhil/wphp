<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

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
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
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
            'attr' => [
                'help_block' => 'person.search.id',
            ],
        ]);

        $builder->add('name', TextType::class, [
            'label' => 'Name',
            'required' => false,
            'attr' => [
                'help_block' => 'person.search.name',
            ],
        ]);

        $builder->add('gender', ChoiceType::class, [
            'label' => 'Gender',
            'choices' => [
                'Female' => Person::FEMALE,
                'Male' => Person::MALE,
                'Transgender' => Person::TRANS,
                'Unknown' => Person::UNKNOWN,
            ],
            'attr' => [
                'help_block' => 'person.search.gender',
            ],
            'required' => false,
            'expanded' => true,
            'multiple' => true,
        ]);

        $builder->add('person_role', ChoiceType::class, [
            'choices' => $roles,
            'choice_label' => fn ($value, $key, $index) => $value->getName(),
            'choice_value' => function ($value) {
                if ($value) {
                    return $value->getId();
                }
            },
            'label' => 'Role',
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'attr' => [
                'help_block' => 'person.search.role',
            ],
        ]);
    }
}
