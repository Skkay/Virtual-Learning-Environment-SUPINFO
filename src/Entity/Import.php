<?php

namespace App\Entity;

use App\Repository\ImportRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

/**
 * @ORM\Entity(repositoryClass=ImportRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Import
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endedAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbFiles;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $currentFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $currentFileName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbLines;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $currentLine;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime());    
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime());
        }

        if ($this->nbFiles === $this->currentFile && $this->nbLines === $this->currentLine) {
            $this->setEndedAt(new \DateTime());
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->startedAt;
    }

    public function setStartedAt(?\DateTimeInterface $startedAt): self
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeInterface
    {
        return $this->endedAt;
    }

    public function setEndedAt(?\DateTimeInterface $endedAt): self
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    public function getNbFiles(): ?int
    {
        return $this->nbFiles;
    }

    public function setNbFiles(?int $nbFiles): self
    {
        $this->nbFiles = $nbFiles;

        return $this;
    }

    public function getCurrentFile(): ?int
    {
        return $this->currentFile;
    }

    public function setCurrentFile(?int $currentFile): self
    {
        $this->currentFile = $currentFile;

        return $this;
    }

    public function getCurrentFileName(): ?string
    {
        return $this->currentFileName;
    }

    public function setCurrentFileName(?string $currentFileName): self
    {
        $this->currentFileName = $currentFileName;

        return $this;
    }

    public function getNbLines(): ?int
    {
        return $this->nbLines;
    }

    public function setNbLines(?int $nbLines): self
    {
        $this->nbLines = $nbLines;

        return $this;
    }

    public function getCurrentLine(): ?int
    {
        return $this->currentLine;
    }

    public function setCurrentLine(?int $currentLine): self
    {
        $this->currentLine = $currentLine;

        return $this;
    }
}
