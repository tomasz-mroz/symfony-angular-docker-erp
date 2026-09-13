<?php

namespace App\Controller\employee;

use App\Application\Common\ControllerHelper;
use App\Application\Cqrs\Command\Employee\CreateEmployee;
use App\Application\Form\Employee\CreateEmployeeForm;
use App\Application\Response\UuidResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/employee', methods: ['POST'])]
class CreateEmployeeController extends AbstractController
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private ControllerHelper $controllerHelper,
    ) {}

    public function __invoke(): UuidResponse
    {
        /** @var CreateEmployee $command */
        $command = $this->controllerHelper->createAndSubmit(
            command: CreateEmployee::class,
            form: CreateEmployeeForm::class,
            validate: true,
            constructorArgument: $this->controllerHelper->getCurrentUserEntity(),
        );

        $this->messageBus->dispatch($command);

        return new UuidResponse($command);
    }
}
