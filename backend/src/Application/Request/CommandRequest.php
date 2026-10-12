<?php


namespace App\Application\Request;


use Symfony\Component\Form\FormInterface;

class CommandRequest
{
    public function __construct(
        private FormInterface $form,
        private object $command
    )
    {
    }

    /**
     * @return FormInterface
     */
    public function getForm(): FormInterface
    {
        return $this->form;
    }

    /**
     * @return object
     */
    public function getCommand(): object
    {
        return $this->command;
    }

}
