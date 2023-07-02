<?php

namespace App\Contoller;

use App\Entity\Image;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

#[AsController]
class CreateImageController extends AbstractController
{
   
    public function __invoke(Request $request): Image
    {
        $uploaded = $request->files->get('file');
        if (!$uploaded) {
            throw new BadRequestException('"file" is required');
        }
        $image = new Image();
        $image->setFile($uploaded);
        return $image;
    }
}