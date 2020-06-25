<?php

namespace App\Entity;

use App\Repository\DepayementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DepayementRepository::class)
 */
class Depayement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Prof::class, mappedBy="depayement")
     */
    private $prof;

    /**
     * @ORM\ManyToOne(targetEntity=Month::class, inversedBy="depayements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $month;

    public function __construct()
    {
        $this->prof = new ArrayCollection();
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

    /**
     * @return Collection|Prof[]
     */
    public function getProf(): Collection
    {
        return $this->prof;
    }

    public function addProf(Prof $prof): self
    {
        if (!$this->prof->contains($prof)) {
            $this->prof[] = $prof;
            $prof->setDepayement($this);
        }

        return $this;
    }

    public function removeProf(Prof $prof): self
    {
        if ($this->prof->contains($prof)) {
            $this->prof->removeElement($prof);
            // set the owning side to null (unless already changed)
            if ($prof->getDepayement() === $this) {
                $prof->setDepayement(null);
            }
        }

        return $this;
    }

    public function getMonth(): ?Month
    {
        return $this->month;
    }

    public function setMonth(?Month $month): self
    {
        $this->month = $month;

        return $this;
    }
}
