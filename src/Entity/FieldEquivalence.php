<?php

namespace App\Entity;

use App\Repository\FieldEquivalenceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FieldEquivalenceRepository::class)
 */
class FieldEquivalence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="json")
     */
    private $equivalence = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getEquivalence(): ?array
    {
        return $this->equivalence;
    }

    public function setEquivalence(array $equivalence): self
    {
        $this->equivalence = $equivalence;

        return $this;
    }
}
