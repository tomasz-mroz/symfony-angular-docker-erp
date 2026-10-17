<?php

declare(strict_types=1);

namespace App\Application\Cqrs\CommandHandler\Auth;

use App\Application\Cqrs\Command\Auth\LoginUser;
use App\Entity\LoginLog;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

#[AsMessageHandler]
final class LoginUserHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly JWTTokenManagerInterface $jwtManager,
        private readonly RequestStack $requestStack,
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

        $request = $this->requestStack->getCurrentRequest();
        $ipAddress = $request?->getClientIp();
        $userAgent = $request?->headers->get('User-Agent');

        $loginLog = new LoginLog($user, $ipAddress, $userAgent);
        $this->entityManager->persist($loginLog);
        $this->entityManager->flush();

        return $this->jwtManager->create($user);
    }
}
