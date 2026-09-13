<?php

namespace App\Application\Common;

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
//        private UserRepositoryInterface $userRepository,
//        private LoggerInterface $logger,
//        private ErrorParser $errorParser
    )
    {
    }

    public function getRequestData($returnArray = true)
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request->headers->get('content-type') === 'application/json') {
            $requestArray = json_decode($this->requestStack->getCurrentRequest()->getContent(), $returnArray);

            return $requestArray !== null ? $requestArray : [];
        }

        return $request->request->all();
    }

    public function getQueryData()
    {
        parse_str($this->requestStack->getCurrentRequest()->getQueryString(), $returnArray);

        return $returnArray;
    }

    /**
     * @return UserInterface|null
     */
    public function getCurrentUser(): ?UserInterface
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
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



    public function getRequest(): Request
    {
        return $this->requestStack->getCurrentRequest();
    }

    public function getRequestContent(): string
    {
        return $this->requestStack->getCurrentRequest()->getContent();
    }

    public function createAndSubmit(
        string $command,
        string $form,
        bool $validate = false,
        mixed $constructorArgument = null,
        mixed $secondConstructorArgument = null
    ): object
    {
        $commandObject = new $command($constructorArgument, $secondConstructorArgument);

        $form = $this->formFactory->create(type: $form, data: $commandObject);
        $form->submit($this->getRequestData());

//        if ($validate and !$form->isValid()) {
//            $this->logger->alert(
//                'Błąd formularza :: ' . $command . ' :: ' .
//                json_encode($this->errorParser->getArray($form))
//            );
//            throw new InvalidFormDataException($form);
//        }

        return $commandObject;
    }
}
