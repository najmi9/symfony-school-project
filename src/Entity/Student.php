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

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="student")
     */
    private $notes;

    /**
     * @ORM\ManyToMany(targetEntity=Prof::class, mappedBy="students")
     */
    private $profs;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="student", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->classes = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->profs = new ArrayCollection();
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

    /**
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setStudent($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
            // set the owning side to null (unless already changed)
            if ($note->getStudent() === $this) {
                $note->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Prof[]
     */
    public function getProfs(): Collection
    {
        return $this->profs;
    }

    public function addProf(Prof $prof): self
    {
        if (!$this->profs->contains($prof)) {
            $this->profs[] = $prof;
            $prof->addStudent($this);
        }

        return $this;
    }

    public function removeProf(Prof $prof): self
    {
        if ($this->profs->contains($prof)) {
            $this->profs->removeElement($prof);
            $prof->removeStudent($this);
        }

        return $this;
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


}
