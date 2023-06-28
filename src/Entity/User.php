<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ApiFilter(OrderFilter::class, properties: ['created_at'], arguments: ['orderParameterName' => 'order'])]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['user:read:collection']],
        ),
        new Get(normalizationContext: ['groups' => ['user:read:single']]),
        new Post(denormalizationContext: ['groups' => ['user:write']]),
        new Delete(),
        new Put(denormalizationContext: ['groups' => ['user:update']])
    ]
)]
#[ORM\Table(name: '`user`')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'])]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read:single', 'user:read:collection'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['user:read:single', 'user:read:collection', 'user:write', 'user:update'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:write', 'user:update'])]
    private ?string $password = null;

    #[ORM\Column]
    #[Groups(['user:read:single', 'user:read:collection'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Groups(['user:read:single', 'user:read:collection'])]
    private ?bool $isAdmin = null;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Like::class, orphanRemoval: true)]
    #[Groups(['user:read:single'])]
    private Collection $likes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Follow::class)]
    #[Groups(['user:read:single'])]
    private Collection $follows;

    public function __construct() {
        $this->setCreatedAt(new \DateTimeImmutable());
        $this->likes = new ArrayCollection();
        $this->follows = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function isIsAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setUserId($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getUserId() === $this) {
                $like->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Follow>
     */
    public function getFollows(): Collection
    {
        return $this->follows;
    }

    public function addFollow(Follow $follow): self
    {
        if (!$this->follows->contains($follow)) {
            $this->follows->add($follow);
            $follow->setUser($this);
        }

        return $this;
    }

    public function removeFollow(Follow $follow): self
    {
        if ($this->follows->removeElement($follow)) {
            // set the owning side to null (unless already changed)
            if ($follow->getUser() === $this) {
                $follow->setUser(null);
            }
        }

        return $this;
    }
}
