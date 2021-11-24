<?php

namespace App\Entity;

use App\Repository\CookieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CookieRepository::class)
 */
class Cookie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="cookies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     */
    private $cookies;

    /**
     * @ORM\Column(type="text")
     */
    private $filedLevel;

    /**
     * @ORM\Column(type="text")
     */
    private $villagerLevel;

    /**
     * @ORM\Column(type="text")
     */
    private $golemLevel;

    /**
     * @ORM\Column(type="text")
     */
    private $golemBoost;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCookies(): ?string
    {
        return $this->cookies;
    }

    public function setCookies(string $cookies): self
    {
        $this->cookies = $cookies;

        return $this;
    }

    public function getFiledLevel(): ?string
    {
        return $this->filedLevel;
    }

    public function setFiledLevel(string $filedLevel): self
    {
        $this->filedLevel = $filedLevel;

        return $this;
    }

    public function getVillagerLevel(): ?string
    {
        return $this->villagerLevel;
    }

    public function setVillagerLevel(string $villagerLevel): self
    {
        $this->villagerLevel = $villagerLevel;

        return $this;
    }

    public function getGolemLevel(): ?string
    {
        return $this->golemLevel;
    }

    public function setGolemLevel(string $golemLevel): self
    {
        $this->golemLevel = $golemLevel;

        return $this;
    }

    public function getGolemBoost(): ?string
    {
        return $this->golemBoost;
    }

    public function setGolemBoost(string $golemBoost): self
    {
        $this->golemBoost = $golemBoost;

        return $this;
    }
}
