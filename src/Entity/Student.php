<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 */
class Student
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="student", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="students")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @ORM\ManyToOne(targetEntity=Level::class, inversedBy="students")
     * @ORM\JoinColumn(nullable=false)
     */
    private $level;

    /**
     * @ORM\ManyToMany(targetEntity=Module::class, inversedBy="students")
     */
    private $modules;

    /**
     * @ORM\ManyToOne(targetEntity=Level::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $entryLevel;

    /**
     * @ORM\Column(type="integer")
     */
    private $entryYear;
    
    /**
     * @ORM\ManyToOne(targetEntity=Level::class)
     */
    private $exitLevel;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $exitYear;

    /**
     * @ORM\Column(type="boolean")
     */
    private $professionalTrainingContract;

    /**
     * @ORM\Column(type="boolean")
     */
    private $accountsPaid;

    /**
     * @ORM\Column(type="float")
     */
    private $accountsPaymentDue;

    /**
     * @ORM\Column(type="boolean")
     */
    private $accountsReminded;

    /**
     * @ORM\ManyToOne(targetEntity=AccountsPaymentType::class, inversedBy="students")
     * @ORM\JoinColumn(nullable=false)
     */
    private $accountsPaymentType;

    /**
     * @ORM\ManyToOne(targetEntity=CompanyTrainingContract::class, inversedBy="students")
     */
    private $companyTrainingContract;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateStartContract;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $employedAs;

    /**
     * @ORM\ManyToOne(targetEntity=Diploma::class, inversedBy="students")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lastDiploma;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfAbsences;

    /**
     * @ORM\OneToMany(targetEntity=Grade::class, mappedBy="student")
     */
    private $grades;

    /**
     * @ORM\Column(type="date")
     */
    private $dateOfBirth;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity=Gender::class, inversedBy="students")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gender;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="students")
     * @ORM\JoinColumn(nullable=false)
     */
    private $region;

    public function __construct()
    {
        $this->modules = new ArrayCollection();
        $this->grades = new ArrayCollection();
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

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getLevel(): ?Level
    {
        return $this->level;
    }

    public function setLevel(?Level $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return Collection|Module[]
     */
    public function getModules(): Collection
    {
        return $this->modules;
    }

    public function addModule(Module $module): self
    {
        if (!$this->modules->contains($module)) {
            $this->modules[] = $module;
        }

        return $this;
    }

    public function removeModule(Module $module): self
    {
        $this->modules->removeElement($module);

        return $this;
    }

    public function getEntryLevel(): ?Level
    {
        return $this->entryLevel;
    }

    public function setEntryLevel(?Level $entryLevel): self
    {
        $this->entryLevel = $entryLevel;

        return $this;
    }

    public function getEntryYear(): ?int
    {
        return $this->entryYear;
    }

    public function setEntryYear(int $entryYear): self
    {
        $this->entryYear = $entryYear;

        return $this;
    }
    
    public function getExitLevel(): ?Level
    {
        return $this->exitLevel;
    }

    public function setExitLevel(?Level $exitLevel): self
    {
        $this->exitLevel = $exitLevel;

        return $this;
    }

    public function getExitYear(): ?int
    {
        return $this->exitYear;
    }

    public function setExitYear(?int $exitYear): self
    {
        $this->exitYear = $exitYear;

        return $this;
    }

    public function getProfessionalTrainingContract(): ?bool
    {
        return $this->professionalTrainingContract;
    }

    public function setProfessionalTrainingContract(bool $professionalTrainingContract): self
    {
        $this->professionalTrainingContract = $professionalTrainingContract;

        return $this;
    }

    public function getAccountsPaid(): ?bool
    {
        return $this->accountsPaid;
    }

    public function setAccountsPaid(bool $accountsPaid): self
    {
        $this->accountsPaid = $accountsPaid;

        return $this;
    }

    public function getAccountsPaymentDue(): ?float
    {
        return $this->accountsPaymentDue;
    }

    public function setAccountsPaymentDue(float $accountsPaymentDue): self
    {
        $this->accountsPaymentDue = $accountsPaymentDue;

        return $this;
    }

    public function isAccountsReminded(): ?bool
    {
        return $this->accountsReminded;
    }

    public function setAccountsReminded(bool $accountsReminded): self
    {
        $this->accountsReminded = $accountsReminded;

        return $this;
    }

    public function getAccountsPaymentType(): ?AccountsPaymentType
    {
        return $this->accountsPaymentType;
    }

    public function setAccountsPaymentType(?AccountsPaymentType $accountsPaymentType): self
    {
        $this->accountsPaymentType = $accountsPaymentType;

        return $this;
    }

    public function getCompanyTrainingContract(): ?CompanyTrainingContract
    {
        return $this->companyTrainingContract;
    }

    public function setCompanyTrainingContract(?CompanyTrainingContract $companyTrainingContract): self
    {
        $this->companyTrainingContract = $companyTrainingContract;

        return $this;
    }

    public function getDateStartContract(): ?\DateTimeInterface
    {
        return $this->dateStartContract;
    }

    public function setDateStartContract(?\DateTimeInterface $dateStartContract): self
    {
        $this->dateStartContract = $dateStartContract;

        return $this;
    }

    public function getEmployedAs(): ?string
    {
        return $this->employedAs;
    }

    public function setEmployedAs(?string $employedAs): self
    {
        $this->employedAs = $employedAs;

        return $this;
    }

    public function getLastDiploma(): ?Diploma
    {
        return $this->lastDiploma;
    }

    public function setLastDiploma(?Diploma $lastDiploma): self
    {
        $this->lastDiploma = $lastDiploma;

        return $this;
    }

    public function getNumberOfAbsences(): ?int
    {
        return $this->numberOfAbsences;
    }

    public function setNumberOfAbsences(int $numberOfAbsences): self
    {
        $this->numberOfAbsences = $numberOfAbsences;

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
            $grade->setStudent($this);
        }

        return $this;
    }

    public function removeGrade(Grade $grade): self
    {
        if ($this->grades->removeElement($grade)) {
            // set the owning side to null (unless already changed)
            if ($grade->getStudent() === $this) {
                $grade->setStudent(null);
            }
        }

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    public function setGender(?Gender $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

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
