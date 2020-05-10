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
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=StdCv::class, inversedBy="student", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $stdcv;

    /**
     * @ORM\OneToOne(targetEntity=StdChoice::class, inversedBy="student", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $stdchoice;

    /**
     * @ORM\OneToOne(targetEntity=StdProfile::class, inversedBy="student", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $profile;

    /**
     * @ORM\OneToOne(targetEntity=StdPerInfo::class, inversedBy="student", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $stdperinfo;

    /**
     * @ORM\ManyToMany(targetEntity=Classe::class, mappedBy="students")
     */
    private $classes;

    public function __construct()
    {
        $this->classes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStdcv(): ?StdCv
    {
        return $this->stdcv;
    }

    public function setStdcv(StdCv $stdcv): void
    {
        $this->stdcv = $stdcv;
    }

    public function getStdchoice(): ?StdChoice
    {
        return $this->stdchoice;
    }

    public function setStdchoice(StdChoice $stdchoice): void
    {
        $this->stdchoice = $stdchoice;
    }

    public function getProfile(): ?StdProfile
    {
        return $this->profile;
    }

    public function setProfile(StdProfile $profile): void
    {
        $this->profile = $profile;
    }

    public function getStdperinfo(): ?StdPerInfo
    {
        return $this->stdperinfo;
    }

    public function setStdperinfo(StdPerInfo $stdperinfo): void
    {
        $this->stdperinfo = $stdperinfo;       
    }

    /**
     * @return Collection|Classe[]
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClass(Classe $class): self
    {
        if (!$this->classes->contains($class)) {
            $this->classes[] = $class;
            $class->addStudent($this);
        }

        return $this;
    }

    public function removeClass(Classe $class): self
    {
        if ($this->classes->contains($class)) {
            $this->classes->removeElement($class);
            $class->removeStudent($this);
        }

        return $this;
    }
}
