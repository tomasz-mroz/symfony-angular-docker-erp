<?php

namespace App\Application\Cqrs\CommandHandler\Auth;

use App\Application\Cqrs\Command\Auth\LoginUser;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

#[AsMessageHandler]
class LoginUserHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private JWTTokenManagerInterface $jwtManager
    ) {}

    public function __invoke(LoginUser $command): string
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $command->email]);

        if (!$user instanceof User) {
            throw new BadCredentialsException('Nieprawidłowy e-mail lub hasło.');
        }

        if (!$this->passwordHasher->isPasswordValid($user, $command->password)) {
            throw new BadCredentialsException('Nieprawidłowy e-mail lub hasło.');
        }

        return $this->jwtManager->create($user);
    }
}
