<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ModuleRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ModuleRepository::class)
 * @UniqueEntity(fields={"label"}, message="There is already a module with this label")
 */
class Module
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
    private $label;

    /**
     * @ORM\ManyToMany(targetEntity=Instructor::class, mappedBy="modules")
     */
    private $instructors;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $speciality;

    /**
     * @ORM\ManyToMany(targetEntity=Student::class, mappedBy="modules")
     */
    private $students;

    /**
     * @ORM\OneToMany(targetEntity=Grade::class, mappedBy="module")
     */
    private $grades;

    public function __construct()
    {
        $this->instructors = new ArrayCollection();
        $this->students = new ArrayCollection();
        $this->grades = new ArrayCollection();
    }

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

    /**
     * @return Collection|Instructor[]
     */
    public function getInstructors(): Collection
    {
        return $this->instructors;
    }

    public function addInstructor(Instructor $instructor): self
    {
        if (!$this->instructors->contains($instructor)) {
            $this->instructors[] = $instructor;
            $instructor->addModule($this);
        }

        return $this;
    }

    public function removeInstructor(Instructor $instructor): self
    {
        if ($this->instructors->removeElement($instructor)) {
            $instructor->removeModule($this);
        }

        return $this;
    }

    public function isSpeciality(): ?bool
    {
        return $this->speciality;
    }

    public function setSpeciality(?bool $speciality): self
    {
        $this->speciality = $speciality;

        return $this;
    }

    /**
     * @return Collection|Student[]
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students[] = $student;
            $student->addModule($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->students->removeElement($student)) {
            $student->removeModule($this);
        }

        return $this;
    }

    /**
     * @return Collection|Grade[]
     */
    public function getGrades(): Collection
    {
        return $this->grades;
    }

    public function addGrade(Grade $grade): self
    {
        if (!$this->grades->contains($grade)) {
            $this->grades[] = $grade;
            $grade->setModule($this);
        }

        return $this;
    }

    public function removeGrade(Grade $grade): self
    {
        if ($this->grades->removeElement($grade)) {
            // set the owning side to null (unless already changed)
            if ($grade->getModule() === $this) {
                $grade->setModule(null);
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
            $value->setModule($this);
        }

        return $this;
    }

    public function __remove($name, $value): self
    {
        if ($this->$name->removeElement($value)) {
            // set the owning side to null (unless already changed)
            if ($value->getModule() === $this) {
                $value->setModule(null);
            }
        }

        return $this;
    }
}
