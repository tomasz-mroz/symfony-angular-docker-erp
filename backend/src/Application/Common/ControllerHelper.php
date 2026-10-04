<?php

namespace App\Application\Common;

use App\Application\Common\Exception\InvalidFormDataException;
use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ControllerHelper
{
    public function __construct(
        private RequestStack $requestStack,
        private TokenStorageInterface $tokenStorage,
        private FormFactoryInterface $formFactory,
        private FormErrorParser $errorParser,
        private LoggerInterface $logger,
    ) {
    }

    public function getRequestData(bool $returnArray = true): array|object
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request === null) {
            return $returnArray ? [] : new \stdClass();
        }

        if (str_contains((string) $request->headers->get('content-type'), 'application/json')) {
            $data = json_decode($request->getContent(), $returnArray);

            return $data ?? ($returnArray ? [] : new \stdClass());
        }

        return $request->request->all();
    }

    public function getQueryData(): array
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request === null) {
            return [];
        }

        parse_str($request->getQueryString() ?? '', $result);

        return $result;
    }

    public function getCurrentUser(): ?UserInterface
    {
        $token = $this->tokenStorage->getToken();

        if ($token === null) {
            return null;
        }

        $user = $token->getUser();

        return $user instanceof UserInterface ? $user : null;
    }

    public function getCurrentUserEntity(): ?User
    {
        $user = $this->getCurrentUser();

        return $user instanceof User ? $user : null;
    }

    public function getRequest(): ?Request
    {
        return $this->requestStack->getCurrentRequest();
    }

    public function getRequestContent(): string
    {
        return $this->requestStack->getCurrentRequest()?->getContent() ?? '';
    }

    public function createAndSubmit(
        string $command,
        string $form,
        bool $validate = false,
        mixed ...$constructorArguments,
    ): object {
        $commandObject = new $command(...$constructorArguments);

        $formObject = $this->formFactory->create(type: $form, data: $commandObject);
        $formObject->submit($this->getRequestData());

        if ($validate && !$formObject->isValid()) {
            $this->logger->alert(
                'Form error :: ' . $command . ' :: ' .
                json_encode($this->errorParser->getArray($formObject))
            );

            throw new InvalidFormDataException($formObject);
        }

        return $commandObject;
    }
}
