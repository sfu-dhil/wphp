<?php

declare(strict_types=1);

namespace App\Form\Firm;

use App\Entity\Firmrole;
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
    public function __construct(private EntityManagerInterface $em) {
    }

    /**
     * Build the form type.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $firmRoleRepo = $this->em->getRepository(Firmrole::class);
        $roles = $firmRoleRepo->findBy([], [
            'name' => 'ASC',
        ]);
        $builder->add('firm_id', TextType::class, [
            'label' => 'Firm ID',
            'required' => false,
            'help' => 'firm.search.id',
        ]);
        $builder->add('firm_name', TextType::class, [
            'label' => 'Firm Name',
            'required' => false,
            'help' => 'firm.search.name',
        ]);
        $builder->add('firm_gender', ChoiceType::class, [
            'label' => 'Gender',
            'choices' => [
                'Female' => 'F',
                'Male' => 'M',
                'Unknown' => 'U',
            ],
            'label_attr' => [
                'class' => 'checkbox-inline',
            ],
            'help' => 'firm.search.gender',
            'required' => false,
            'expanded' => true,
            'multiple' => true,
        ]);

        $builder->add('firm_role', ChoiceType::class, [
            'label' => 'Firm Role',
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
            'help' => 'firm.search.role',
        ]);
        $builder->add('firm_address', TextType::class, [
            'label' => 'Firm Address',
            'required' => false,
            'help' => 'firm.search.streetAddress',
        ]);
    }
}
