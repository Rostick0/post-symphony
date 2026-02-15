<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\OneToMany(targetEntity: Category::class, mappedBy: 'post', orphanRemoval: true)]
    private Collection $categoryId;

    public function __construct()
    {
        $this->categoryId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategoryId(): Collection
    {
        return $this->categoryId;
    }

    public function addCategoryId(Category $categoryId): static
    {
        if (!$this->categoryId->contains($categoryId)) {
            $this->categoryId->add($categoryId);
            $categoryId->setPost($this);
        }

        return $this;
    }

    public function removeCategoryId(Category $categoryId): static
    {
        if ($this->categoryId->removeElement($categoryId)) {
            // set the owning side to null (unless already changed)
            if ($categoryId->getPost() === $this) {
                $categoryId->setPost(null);
            }
        }

        return $this;
    }
}
