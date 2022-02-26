<?php

namespace App\Entity;

use App\Repository\StaffRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StaffRepository::class)
 */
class Staff
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="staff", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Campus::class, inversedBy="staff")
     */
    private $campus;

    public function __construct()
    {
        $this->campus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Campus[]
     */
    public function getCampus(): Collection
    {
        return $this->campus;
    }

    public function addCampus(Campus $campus): self
    {
        if (!$this->campus->contains($campus)) {
            $this->campus[] = $campus;
        }

        return $this;
    }

    public function removeCampus(Campus $campus): self
    {
        $this->campus->removeElement($campus);

        return $this;
    }

    
    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value): self
    {
        $this->$name = $value;

        return $this;
    }

    public function __add($name, $value): self
    {
        if (!$this->$name->contains($value)) {
            $this->$name[] = $value;
        }

        return $this;
    }

    public function __remove($name, $value): self
    {
        $this->$name->removeElement($value);

        return $this;
    }
}
