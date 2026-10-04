<?php

namespace App\Application\Common;

use Symfony\Component\Form\FormInterface;

class FormErrorParser
{
    /**
     * @return array<string, string[]>
     */
    public function getArray(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->getErrors(true) as $error) {
            $origin = $error->getOrigin();
            $name = $origin?->getName() ?? '_global';

            $errors[$name][] = $error->getMessage();
        }

        return $errors;
    }
}
