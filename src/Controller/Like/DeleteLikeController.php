<?php

namespace App\Controller\Like;

use App\Entity\Like;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class DeleteLikeController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $manager)
    {
        $post_id = $request->query->get('postId');
        $user_id = $request->query->get('userId');
        $like = $manager->getRepository(Like::class)->findOneBy(['post_id' => $post_id, 'user_id' => $user_id]);
        $user = $like->getUserId();
        $user->removeLike($like);
        $manager->persist($user);
        $manager->remove($like);
        $manager->flush();
    }
}