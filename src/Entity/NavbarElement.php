<?php

namespace App\Entity;

use App\Repository\NavbarElementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NavbarElementRepository::class)
 */
class NavbarElement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $value;

    /**
     * @ORM\Column(type="integer", options={"default" = 0, "unsigned"=true})
     */
    private $postion;

    /**
     * @ORM\Column(type="string", length=255, nullable=false ,columnDefinition="ENUM('link', 'dropdown')")
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     */
    private $parent_id;

    /**
     * @ORM\Column(type="boolean", options={"default" = 0})
     */
    private $new_tab;

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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getPostion(): ?int
    {
        return $this->postion;
    }

    public function setPostion(int $postion): self
    {
        $this->postion = $postion;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    public function setParentId(?int $parent_id): self
    {
        $this->parent_id = $parent_id;

        return $this;
    }

    public function getNewTab(): ?bool
    {
        return $this->new_tab;
    }

    public function setNewTab(bool $new_tab): self
    {
        $this->new_tab = $new_tab;

        return $this;
    }
}
