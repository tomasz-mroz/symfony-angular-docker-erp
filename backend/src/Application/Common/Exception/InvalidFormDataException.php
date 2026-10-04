<?php

namespace App\Application\Common\Exception;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class InvalidFormDataException extends BadRequestHttpException
{
    public function __construct(
        private readonly FormInterface $form,
        string $message = 'Invalid form data',
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $previous);
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }
}
