<?php

namespace App\Controller\Follow;

use App\Entity\Follow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class DeleteFollowController extends AbstractController
{
    public function __invoke(Int $brand_id, Int $user_id, EntityManagerInterface $manager)
    {
        $data = $manager->getRepository(Follow::class)->findOneBy(['brand' => $brand_id, 'user' => $user_id]);
        $user = $data->getUser();
        $user->removeFollow($data);
        $manager->persist($user);
        $manager->remove($data);
        $manager->flush();
    }
}