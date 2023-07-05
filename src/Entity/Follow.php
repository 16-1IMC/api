<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\FollowRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\Parameter;
use App\Controller\Follow\DeleteFollowController;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FollowRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            denormalizationContext: ['groups' => ['follow:write']]
        ),
        new Delete(
            uriTemplate: '/follows',
            controller: DeleteFollowController::class,
            openapi: new Operation(
                parameters: [
                    new Parameter(
                        name: 'brandId',
                        in: 'query',
                        required: true,
                        schema: ['type' => 'string']
                    ),
                    new Parameter(
                        name: 'userId',
                        in: 'query',
                        required: true,
                        schema: ['type' => 'string']
                    )
                ]
            )
            )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['brand' => 'exact', 'user' => 'exact'])]
class Follow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read:single'])]
    private ?int $id = null;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['user:read:single', 'follow:write'])]
    private ?Brand $brand = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'follows')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['follow:write'])]
    private ?User $user = null;

    #[ORM\Column]
    #[Groups(['user:read:single'])]
    private ?\DateTimeImmutable $created_at = null;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
