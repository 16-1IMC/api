<?php

namespace App\Controller\Follow;

use App\Entity\Follow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class DeleteFollowController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $manager)
    {
        $brand_id = $request->query->get('brandId');
        $user_id = $request->query->get('userId');
        $data = $manager->getRepository(Follow::class)->findOneBy(['brand' => $brand_id, 'user' => $user_id]);
        $user = $data->getUser();
        $user->removeFollow($data);
        $manager->persist($user);
        $manager->remove($data);
        $manager->flush();
    }
}