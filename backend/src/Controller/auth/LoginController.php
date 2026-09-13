<?php

namespace App\Controller\auth;

use App\Application\Common\ControllerHelper;
use App\Application\Cqrs\Command\Auth\LoginUser;
use App\Application\Form\Auth\LoginForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/login', methods: ['POST'])]
class LoginController extends AbstractController
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private ControllerHelper $controllerHelper,
    ) {}

    public function __invoke(): JsonResponse
    {
        /** @var LoginUser $command */
        $command = $this->controllerHelper->createAndSubmit(
            command: LoginUser::class,
            form: LoginForm::class,
            validate: true
        );

        $envelope = $this->messageBus->dispatch($command);

        $token = $envelope->last(HandledStamp::class)->getResult();

        return new JsonResponse(['token' => $token]);
    }
}
