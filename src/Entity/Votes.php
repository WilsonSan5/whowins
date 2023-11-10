<?php

namespace App\Entity;


use ApiPlatform\Metadata\Patch;
use App\Repository\VotesRepository;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    shortName: 'Vote',
    denormalizationContext: ['groups' => ['votes:write']],
    operations: [
        new Patch()
    ]
)]

#[ORM\Entity(repositoryClass: VotesRepository::class)]
class Votes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['fight:read', 'randomFight'])]
    private ?int $id = null;

    #[ORM\Column]

    private ?int $numberOfVote = null;

    #[ORM\ManyToOne(inversedBy: 'votes')]

    private ?Fight $Fight = null;

    #[ORM\ManyToOne(inversedBy: 'votes')]
    #[Groups(['fight:read'])]

    private ?Character $Fighter = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumberOfVote(): ?int
    {
        return $this->numberOfVote;
    }

    #[Groups(['votes:write'])]
    public function setNumberOfVote(int $numberOfVote): static
    {
        $this->numberOfVote = $numberOfVote;

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

    public function getFighter(): ?Character
    {
        return $this->Fighter;
    }

    public function setFighter(?Character $Fighter): static
    {
        $this->Fighter = $Fighter;

        return $this;
    }

}
