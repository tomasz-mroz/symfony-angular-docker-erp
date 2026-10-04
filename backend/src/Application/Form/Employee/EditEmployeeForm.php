<?php

namespace App\Application\Form\Employee;

use App\Application\Cqrs\Command\Employee\EditEmployee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditEmployeeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EditEmployee::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }

    public function getParent()
    {
        return CreateEmployeeForm::class;
    }
}
