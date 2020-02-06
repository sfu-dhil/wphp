<?php

namespace App\Form\Person;

use App\Entity\Role;
use Doctrine\ORM\EntityManager;
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
        $roleRepo = $this->em->getRepository(Role::class);
        $roles = $roleRepo->findAll(array(
            'name' => 'ASC',
        ));
        $builder->add('name', TextType::class, array(
            'label' => 'Name',
            'required' => false,
            'attr' => array(
                'help_block' => 'person.search.name',
            ),
        ));

        $builder->add('gender', ChoiceType::class, array(
            'label' => 'Gender',
            'choices' => array(
                'Female' => 'F',
                'Male' => 'M',
                'Unknown' => 'U',
            ),
            'attr' => array(
                'help_block' => 'person.search.gender',
            ),
            'required' => false,
            'expanded' => true,
            'multiple' => true,
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
            'label' => 'Role',
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'attr' => array(
                'help_block' => 'person.search.role',
            ),
        ));
    }
}
