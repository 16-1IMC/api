<?php

namespace App\Entity;

use DateTimeImmutable;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\ImageRepository;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\Metadata\Post as PostAPI;
use App\Controller\CreateImageController;
use ApiPlatform\OpenApi\Model\RequestBody;
use Symfony\Component\HttpFoundation\File\File;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[Uploadable]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact'])]
#[ApiResource(
    operations: [
        new GetCollection(
            order: ['id' => 'ASC'],
        ),
        new Get(),
        new PostAPI(
            uriTemplate: '/images',
            deserialize: false,
            controller: CreateImageController::class,
            openapi: new Operation(
                requestBody: new RequestBody(
                    content: new \ArrayObject([
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object', 
                                'properties' => [
                                    'file' => [
                                        'type' => 'string', 
                                        'format' => 'binary'
                                    ]
                                ]
                            ]
                        ]
                    ])
                )
            )
        ),
        new Delete()
    ]
)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['image:read:collection', 'post:read:single', 'brand:read:collection', 'brand:read:single', 'post:read:collection'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['image:read:collection', 'post:read:single', 'brand:read:collection', 'brand:read:single', 'post:read:collection'])]
    private ?string $path = null;

    #[ApiProperty(types: ['https://schema.org/contentUrl'])]
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['image:read:collection', 'post:read:single', 'brand:read:collection', 'brand:read:single', 'post:read:collection'])]
    private ?string $contentUrl = null;

    #[UploadableField(mapping: 'image', fileNameProperty: 'path')]
    private ?File $file = null;
    
    #[ORM\Column(length: 255)]
    #[Groups(['image:read:collection', 'post:read:single', 'brand:read:collection', 'brand:read:single', 'post:read:collection'])]
    private ?DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Post $post = null;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTimeImmutable());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getContentUrl(): ?string
    {
        return $this->contentUrl;
    }

    public function setContentUrl(?string $contentUrl): static
    {
        $this->contentUrl = $contentUrl;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;

        return $this;
    }
}
