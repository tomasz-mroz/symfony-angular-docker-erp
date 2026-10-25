<?php

namespace App\Controller\employee;

use App\Application\Common\ControllerHelper;
use App\Application\Cqrs\Command\Employee\EditEmployee;
use App\Application\Form\Employee\EditEmployeeForm;
use App\Application\Response\UuidResponse;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/employee/{uuid}', methods: ['PUT'])]
class EditEmployeeController extends AbstractController
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private ControllerHelper $controllerHelper,
    ) {}

    public function __invoke(string $uuid): UuidResponse
    {
        /** @var EditEmployee $command */
        $command = $this->controllerHelper->createAndSubmit(
            command: EditEmployee::class,
            form: EditEmployeeForm::class,
            validate: true,
            constructorArguments: [
                Uuid::fromString($uuid),
                $this->controllerHelper->getCurrentUserEntity(),
            ],
        );

        $this->messageBus->dispatch($command);

        return new UuidResponse($command);
    }
}
