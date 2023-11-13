<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FightRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FightRepository::class)]
#[ApiResource]
class Fight
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $is_balanced = null;

    #[ORM\ManyToMany(targetEntity: Fighter::class, inversedBy: 'fights')]
    private Collection $Fighters;

    #[ORM\OneToMany(mappedBy: 'Fight', targetEntity: Vote::class)]
    private Collection $votes;


    public function __construct()
    {
        $this->Fighters = new ArrayCollection();
        $this->votes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsBalanced(): ?bool
    {
        return $this->is_balanced;
    }

    public function setIsBalanced(bool $is_balanced): static
    {
        $this->is_balanced = $is_balanced;

        return $this;
    }

    /**
     * @return Collection<int, Fighter>
     */
    public function getFighters(): Collection
    {
        return $this->Fighters;
    }

    public function addFighter(Fighter $fighter): static
    {
        if (!$this->Fighters->contains($fighter)) {
            $this->Fighters->add($fighter);
        }

        return $this;
    }

    public function removeFighter(Fighter $fighter): static
    {
        $this->Fighters->removeElement($fighter);

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
            $vote->setFight($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): static
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getFight() === $this) {
                $vote->setFight(null);
            }
        }

        return $this;
    }

    public function resetVote(): static
    {
        $votes = $this->getVotes();
        foreach ($votes as $vote) {
            $vote->setNumberOfVotes(0);
        }
        return $this;
    }
}
