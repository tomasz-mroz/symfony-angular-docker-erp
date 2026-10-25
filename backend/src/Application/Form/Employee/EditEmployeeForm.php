<?php

namespace App\Application\Form\Employee;

use App\Application\Cqrs\Command\Employee\EditEmployee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditEmployeeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'constraints' => [new NotBlank()]
            ])
            ->add('firstName', TextType::class, [
                'constraints' => [new NotBlank()]
            ])
            ->add('lastName', TextType::class, [
                'constraints' => [new NotBlank()]
            ])
            ->add('role', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EditEmployee::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }
}
