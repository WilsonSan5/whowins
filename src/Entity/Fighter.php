<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FighterRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Post;

#[ORM\Entity(repositoryClass: FighterRepository::class)]
#[ApiResource(operations: [
    'POST' => new Post()
],
    denormalizationContext: ['groups' => ['fighter:write']]
)]
class Fighter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['fight:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['fight:read', 'fighter:write'])]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $strength = 0;

    #[ORM\Column]
    private ?bool $is_valid = false;

    #[ORM\ManyToOne(inversedBy: 'fighters')]
    private ?Category $category = null;

    #[ORM\ManyToMany(targetEntity: Fight::class, mappedBy: 'Fighters')]
    private Collection $fights;

    #[ORM\OneToMany(mappedBy: 'Fighter', targetEntity: Vote::class)]
    private Collection $votes;

    public function __construct()
    {
        $this->fights = new ArrayCollection();
        $this->votes = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->name;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStrength(): ?int
    {
        return $this->strength;
    }

    public function setStrength(int $strength): static
    {
        $this->strength = $strength;

        return $this;
    }

    public function isIsValid(): ?bool
    {
        return $this->is_valid;
    }

    public function setIsValid(bool $is_valid): static
    {
        $this->is_valid = $is_valid;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Fight>
     */
    public function getFights(): Collection
    {
        return $this->fights;
    }

    public function addFight(Fight $fight): static
    {
        if (!$this->fights->contains($fight)) {
            $this->fights->add($fight);
            $fight->addFighter($this);
        }

        return $this;
    }

    public function removeFight(Fight $fight): static
    {
        if ($this->fights->removeElement($fight)) {
            $fight->removeFighter($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Vote>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): static
    {
        if (!$this->votes->contains($vote)) {
            $this->votes->add($vote);
            $vote->setFighter($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): static
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getFighter() === $this) {
                $vote->setFighter(null);
            }
        }

        return $this;
    }
}
