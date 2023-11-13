<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Fighter::class)]
    private Collection $fighters;

    public function __toString()
    {
        return $this->title;
    }

    public function __construct()
    {
        $this->fighters = new ArrayCollection();
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, Fighter>
     */
    public function getFighters(): Collection
    {
        return $this->fighters;
    }

    public function addFighter(Fighter $fighter): static
    {
        if (!$this->fighters->contains($fighter)) {
            $this->fighters->add($fighter);
            $fighter->setCategory($this);
        }

        return $this;
    }

    public function removeFighter(Fighter $fighter): static
    {
        if ($this->fighters->removeElement($fighter)) {
            // set the owning side to null (unless already changed)
            if ($fighter->getCategory() === $this) {
                $fighter->setCategory(null);
            }
        }

        return $this;
    }
}
