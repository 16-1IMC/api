<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserPasswordHasher implements ProcessorInterface
{
    public function __construct(private readonly ProcessorInterface $processor, private readonly UserPasswordHasherInterface $passwordHasher){
    }

    public function process($user, Operation $operation, array $uriVariables = [], array $context = []) {
        if (!$user->getPlainPassword()) {
            return $this->processor->process($user, $operation, $uriVariables, $context);
        }
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $user->getPlainPassword()
        );
        $user->setPassword($hashedPassword);
        $user->eraseCredentials();
        return $this->processor->process($user, $operation, $uriVariables, $context);
    }
}