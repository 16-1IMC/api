<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PostRepository;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post as PostApi;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['post:read:collection']],
        ),
        new Get(normalizationContext: ['groups' => ['post:read:single']]),
        new PostApi(
            denormalizationContext: ['groups' => ['post:write:data']]
        ),
        new Delete(),
        new Put(denormalizationContext: ['groups' => ['post:update']])
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'author' => 'exact', 'title' => 'partial'])]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['post:read:collection', 'post:read:single', 'brand:read:single'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['post:read:collection', 'post:read:single', 'post:write:data', 'post:update', 'brand:read:single'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['post:read:collection', 'post:read:single', 'post:write:data', 'post:update', 'brand:read:single'])]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['post:read:collection', 'post:read:single', 'brand:read:single'])]
    private ?\DateTimeInterface $created_at = null;

    #[Groups(['post:read:collection', 'post:read:single', 'brand:read:single'])]
    #[ORM\OneToMany(mappedBy: 'post_id', targetEntity: Like::class, orphanRemoval: true)]
    private Collection $likes;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post:read:collection', 'post:read:single', 'post:write:data'])]
    private ?Brand $author = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Image::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['post:read:collection', 'post:read:single', 'post:write:data', 'brand:read:single'])]
    private Collection $images;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

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
            $like->setPostId($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getPostId() === $this) {
                $like->setPostId(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?Brand
    {
        return $this->author;
    }

    public function setAuthor(?Brand $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setPost($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getPost() === $this) {
                $image->setPost(null);
            }
        }

        return $this;
    }
}
