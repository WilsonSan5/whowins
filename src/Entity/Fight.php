<?php

namespace App\Entity;


use ApiPlatform\Metadata\GetCollection;
use App\Controller\GetRandomFightController;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\FightRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    normalizationContext: ['groups' => ['fight:read']],
    operations: [
        new GetCollection(
            name: 'getRandomFight',
            uriTemplate: '/fights/random',
            controller: GetRandomFightController::class,
        )
    ],
    paginationEnabled: false,
)]
#[ApiFilter(PropertyFilter::class)]
#[ApiFilter(SearchFilter::class, strategy: 'exact')]
#[ORM\Entity(repositoryClass: FightRepository::class)]
class Fight
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['fight:read'])]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $isBalanced = null;

    #[ORM\Column]
    private ?bool $isValid = null;

    #[ORM\OneToMany(mappedBy: 'Fight', targetEntity: Votes::class)]
    #[Groups(['fight:read'])]
    private Collection $votes;

    public function __construct()
    {
        $this->votes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsBalanced(): ?bool
    {
        return $this->isBalanced;
    }

    public function setIsBalanced(bool $isBalanced): static
    {
        $this->isBalanced = $isBalanced;

        return $this;
    }

    public function isIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(bool $isValid): static
    {
        $this->isValid = $isValid;

        return $this;
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
            $vote->setFight($this);
        }

        return $this;
    }

    public function removeVote(Votes $vote): static
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getFight() === $this) {
                $vote->setFight(null);
            }
        }

        return $this;
    }

}
