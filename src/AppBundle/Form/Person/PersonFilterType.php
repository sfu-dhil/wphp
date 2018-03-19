<?php

namespace AppBundle\Form\Person;

use AppBundle\Entity\Role;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * PersonFilterType is a subform used in the title search form.
 */
class PersonFilterType extends AbstractType
{

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
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $roleRepo = $this->em->getRepository(Role::class);
        $roles = $roleRepo->findAll(array(
            'name' => 'ASC',
        ));
        $builder->add('name', TextType::class, array(
            'label' => 'Name',
            'required' => false,
            'attr' => array(
                'help_block' => 'Enter all or part of a personal name'
            ),
        ));

        $builder->add('gender', ChoiceType::class, array(
            'label' => 'Gender',
            'choices' => array(
                'Female' => 'F',
                'Male' => 'M',
                '(unknown)' => 'U',
            ),
            'attr' => array(
                'help_block' => 'Leave this field blank to include all genders'
            ),
            'required' => false,
            'expanded' => true,
            'multiple' => true,
        ));

        $builder->add('person_role', ChoiceType::class, array(
            'choices' => $roles,
            'choice_label' => function($value, $key, $index) {
                return $value->getName();
            },
            'choice_value' => function($value) {
                if($value) {
                    return $value->getId();
                }
            },
            'label' => 'Firm Role',
            'required' => false,
            'expanded' => true,
            'multiple' => true,
        ));
    }
}
