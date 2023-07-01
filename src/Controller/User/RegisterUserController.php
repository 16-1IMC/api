<?php

namespace App\Controller\User;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ObjectManager;


#[AsController]
class RegisterUserController extends AbstractController
{
    public function __invoke(User $data, UserPasswordHasherInterface $passwordHasher, ObjectManager $entityManager): User
    {
        $data->setPassword(
            $this->$passwordHasher->hashPassword($data, $data->getPassword())
        );

        $data->setRoles(['USER']);

        $this->$entityManager->persist($data);
        $this->$entityManager->flush();

        return $data;
    }
}