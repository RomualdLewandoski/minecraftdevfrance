<?php

namespace App\Entity;

use App\Repository\NavbarMenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ORM\Entity(repositoryClass=NavbarMenuRepository::class)
 */
class NavbarMenu
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isNewtab;

    /**
     * @ORM\OneToMany(targetEntity=NavbarSubMenu::class, mappedBy="parent", orphanRemoval=true)
     * @OrderBy({"position" = "ASC"})
     */
    private $navbarSubMenus;

    public function __construct()
    {
        $this->navbarSubMenus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getIsNewtab(): ?bool
    {
        return $this->isNewtab;
    }

    public function setIsNewtab(bool $isNewtab): self
    {
        $this->isNewtab = $isNewtab;

        return $this;
    }

    /**
     * @return Collection|NavbarSubMenu[]
     */
    public function getNavbarSubMenus(): Collection
    {
        return $this->navbarSubMenus;
    }

    public function addNavbarSubMenu(NavbarSubMenu $navbarSubMenu): self
    {
        if (!$this->navbarSubMenus->contains($navbarSubMenu)) {
            $this->navbarSubMenus[] = $navbarSubMenu;
            $navbarSubMenu->setParent($this);
        }

        return $this;
    }

    public function removeNavbarSubMenu(NavbarSubMenu $navbarSubMenu): self
    {
        if ($this->navbarSubMenus->contains($navbarSubMenu)) {
            $this->navbarSubMenus->removeElement($navbarSubMenu);
            // set the owning side to null (unless already changed)
            if ($navbarSubMenu->getParent() === $this) {
                $navbarSubMenu->setParent(null);
            }
        }

        return $this;
    }
}
