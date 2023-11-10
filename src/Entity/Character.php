<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: []
)]

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
#[ORM\Table(name: '`character`')]
class Character
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['fight:read', 'votes:write'])]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['fight:read'])]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $strength = null;

    #[ORM\ManyToOne(inversedBy: 'characters')]
    private ?Category $Category = null;

    #[ORM\ManyToMany(targetEntity: Fight::class, mappedBy: 'Fighter')]
    private Collection $fights;

    #[ORM\OneToMany(mappedBy: 'Fighter', targetEntity: Votes::class)]
    private Collection $Fight;

    #[ORM\OneToMany(mappedBy: 'Fighter', targetEntity: Votes::class)]
    private Collection $votes;

    public function __construct()
    {
        $this->fights = new ArrayCollection();
        $this->Fight = new ArrayCollection();
        $this->votes = new ArrayCollection();
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

    public function getCategory(): ?Category
    {
        return $this->Category;
    }

    public function setCategory(?Category $Category): static
    {
        $this->Category = $Category;

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
     * @return Collection<int, Votes>
     */
    public function getFight(): Collection
    {
        return $this->Fight;
    }

    /**
     * @return Collection<int, Votes>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Votes $vote): static
    {
        if (!$this->votes->contains($vote)) {
            $this->votes->add($vote);
            $vote->setFighter($this);
        }

        return $this;
    }

    public function removeVote(Votes $vote): static
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
