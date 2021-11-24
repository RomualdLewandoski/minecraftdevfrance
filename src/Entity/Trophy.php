<?php

namespace App\Entity;

use App\Repository\TrophyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrophyRepository::class)
 */
class Trophy
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $bgColor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSuper;

    /**
     * @ORM\Column(type="integer")
     */
    private $action;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @ORM\OneToMany(targetEntity=UserTrophy::class, mappedBy="trophy", orphanRemoval=true)
     */
    private $userTrophies;

    public function __construct()
    {
        $this->userTrophies = new ArrayCollection();
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

    public function getBgColor(): ?string
    {
        return $this->bgColor;
    }

    public function setBgColor(string $bgColor): self
    {
        $this->bgColor = $bgColor;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsSuper(): ?bool
    {
        return $this->isSuper;
    }

    public function setIsSuper(bool $isSuper): self
    {
        $this->isSuper = $isSuper;

        return $this;
    }

    public function getAction(): ?int
    {
        return $this->action;
    }

    public function setAction(int $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Collection|UserTrophy[]
     */
    public function getUserTrophies(): Collection
    {
        return $this->userTrophies;
    }

    public function addUserTrophy(UserTrophy $userTrophy): self
    {
        if (!$this->userTrophies->contains($userTrophy)) {
            $this->userTrophies[] = $userTrophy;
            $userTrophy->setTrophy($this);
        }

        return $this;
    }

    public function removeUserTrophy(UserTrophy $userTrophy): self
    {
        if ($this->userTrophies->contains($userTrophy)) {
            $this->userTrophies->removeElement($userTrophy);
            // set the owning side to null (unless already changed)
            if ($userTrophy->getTrophy() === $this) {
                $userTrophy->setTrophy(null);
            }
        }

        return $this;
    }
}
