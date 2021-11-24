<?php

namespace App\Entity;

use App\Repository\UserRankRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRankRepository::class)
 */
class UserRank
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $canManageForum;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasRepportPanel;

    /**
     * @ORM\Column(type="boolean")
     */
    private $canManageWall;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Priority;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="UserRanks")
     */
    private $users;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDisplayStaff;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCanManageForum(): ?bool
    {
        return $this->canManageForum;
    }

    public function setCanManageForum(bool $canManageForum): self
    {
        $this->canManageForum = $canManageForum;

        return $this;
    }

    public function getHasRepportPanel(): ?bool
    {
        return $this->hasRepportPanel;
    }

    public function setHasRepportPanel(bool $hasRepportPanel): self
    {
        $this->hasRepportPanel = $hasRepportPanel;

        return $this;
    }

    public function getCanManageWall(): ?bool
    {
        return $this->canManageWall;
    }

    public function setCanManageWall(bool $canManageWall): self
    {
        $this->canManageWall = $canManageWall;

        return $this;
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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->Priority;
    }

    public function setPriority(?int $Priority): self
    {
        $this->Priority = $Priority;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addUserRank($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeUserRank($this);
        }

        return $this;
    }

    public function getIsDisplayStaff(): ?bool
    {
        return $this->isDisplayStaff;
    }

    public function setIsDisplayStaff(bool $isDisplayStaff): self
    {
        $this->isDisplayStaff = $isDisplayStaff;

        return $this;
    }
}
