<?php

namespace AppBundle\Form\Firm;

use AppBundle\Entity\Firmrole;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * FirmFilterType is a subform used in the title search form. The form is
 * registered as a service so that the doctrine registry is automatically
 * injected as a dependency.
 */
class FirmFilterType extends AbstractType {

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
     * Build the form type.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $firmRoleRepo = $this->em->getRepository(Firmrole::class);
        $roles = $firmRoleRepo->findAll(array(
            'name' => 'ASC',
        ));
        $builder->add('firm_name', TextType::class, array(
            'label' => 'Firm Name',
            'required' => false,
        ));
        $builder->add('firm_role', ChoiceType::class, array(
            'choices' => $roles,
            'choice_label' => function($value, $key, $index) {
                return $value->getName();
            },
            'choice_value' => function($value) {
                if ($value) {
                    return $value->getId();
                }
            },
            'label' => 'Firm Role',
            'required' => false,
            'expanded' => true,
            'multiple' => true,
        ));
        $builder->add('firm_address', TextType::class, array(
            'label' => 'Firm Address',
            'required' => false,
        ));
    }

}
