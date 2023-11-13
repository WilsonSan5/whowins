<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\VoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoteRepository::class)]
#[ApiResource]
class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numberOfVotes = null;

    #[ORM\ManyToOne(inversedBy: 'votes')]
    private ?Fight $Fight = null;

    #[ORM\ManyToOne(inversedBy: 'votes')]
    private ?Fighter $Fighter = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumberOfVotes(): ?int
    {
        return $this->numberOfVotes;
    }

    public function setNumberOfVotes(int $numberOfVotes): static
    {
        $this->numberOfVotes = $numberOfVotes;

        return $this;
    }

    public function getFight(): ?Fight
    {
        return $this->Fight;
    }

    public function setFight(?Fight $Fight): static
    {
        $this->Fight = $Fight;

        return $this;
    }

    public function getFighter(): ?Fighter
    {
        return $this->Fighter;
    }

    public function setFighter(?Fighter $Fighter): static
    {
        $this->Fighter = $Fighter;

        return $this;
    }
}
