<?php

namespace App\Entity;

use App\Entity\Category;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use App\Entity\SocialNetwork;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\BrandRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post as PostApi;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;

#[ORM\Entity(repositoryClass: BrandRepository::class)]
#[ORM\Table(name: '`brand`')]
#[UniqueEntity(fields: ['email'])]
#[ApiFilter(OrderFilter::class, properties: ['created_at'], arguments: ['orderParameterName' => 'order'])]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['brand:read:collection']],
            order: ['created_at' => 'DESC']
        ),
        new Get(normalizationContext: ['groups' => ['brand:read:single']]),
        new PostApi(denormalizationContext: ['groups' => ['brand:write']]),
        new Delete(),
        new Put(denormalizationContext: ['groups' => ['brand:update']])
    ]
)]
class Brand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['brand:read:single', 'brand:read:collection'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['brand:read:single', 'brand:read:collection', 'brand:write', 'brand:update'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['brand:read:single', 'brand:read:collection', 'brand:write', 'brand:update'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(['brand:write', 'brand:update'])]
    private ?string $password = null;

    #[ORM\Column]
    #[Groups(['brand:read:single', 'brand:read:collection'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'brands')]
    #[Groups(['brand:read:single'])]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'brand_id', targetEntity: SocialNetwork::class)]
    #[Groups(['brand:read:single'])]
    private Collection $socialNetworks;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Post::class)]
    #[Groups(['brand:read:single'])]
    private Collection $posts;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->socialNetworks = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addBrand($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeBrand($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, SocialNetwork>
     */
    public function getSocialNetworks(): Collection
    {
        return $this->socialNetworks;
    }

    public function addSocialNetwork(SocialNetwork $socialNetwork): self
    {
        if (!$this->socialNetworks->contains($socialNetwork)) {
            $this->socialNetworks->add($socialNetwork);
            $socialNetwork->setBrandId($this);
        }

        return $this;
    }

    public function removeSocialNetwork(SocialNetwork $socialNetwork): self
    {
        if ($this->socialNetworks->removeElement($socialNetwork)) {
            // set the owning side to null (unless already changed)
            if ($socialNetwork->getBrandId() === $this) {
                $socialNetwork->setBrandId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }
}
