<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 */
class Company
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sector;

    /**
     * @ORM\OneToMany(targetEntity=Student::class, mappedBy="companyTrainingContract")
     */
    private $studentsTrainingContract;

    public function __construct()
    {
        $this->studentsTrainingContract = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getSector(): ?string
    {
        return $this->sector;
    }

    public function setSector(?string $sector): self
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * @return Collection|Student[]
     */
    public function getStudentsTrainingContract(): Collection
    {
        return $this->studentsTrainingContract;
    }

    public function addStudentTrainingContract(Student $student): self
    {
        if (!$this->studentsTrainingContract->contains($student)) {
            $this->studentsTrainingContract[] = $student;
            $student->setCompanyTrainingContract($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->studentsTrainingContract->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getCompanyTrainingContract() === $this) {
                $student->setCompanyTrainingContract(null);
            }
        }

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

            if (method_exists($value, 'setCompanyTrainingContract')) {
                $value->setCompanyTrainingContract($this);
            }

            if (method_exists($value, 'addCompanyTrainingContract')) {
                $value->addCompanyTrainingContract($this);
            }
        }

        return $this;
    }
}
