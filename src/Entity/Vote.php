<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Patch;
use App\Repository\VoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VoteRepository::class)]
#[ApiResource(
    operations: ['PATCH' => new Patch()],
    denormalizationContext: ['groups' => ['vote:write']],
)]
class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['fight:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['fight:read', 'vote:write'])]
    private ?int $numberOfVotes = null;

    #[ORM\ManyToOne(inversedBy: 'votes')]
    private ?Fight $Fight = null;

    #[ORM\ManyToOne(inversedBy: 'votes')]
    #[Groups(['fight:read'])]
    private ?Fighter $Fighter = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumberOfVotes(): ?int
    {
        return $this->numberOfVotes;
    }

    #[Groups(['fight:read', 'vote:write'])]
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

    public function addVote(int $numberOfVotes): static
    {
        $this->numberOfVotes++;
        return $this;
    }
}
