<?php

namespace App\Entity;

use App\Repository\UserInfoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserInfoRepository::class)
 */
class UserInfo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="userInfo", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $gender;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isGender;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $birthDate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isBirthDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $homePage;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isHomePage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isCountry;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $job;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isJob;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $steam;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isSteam;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $minecraft;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isMinecraft;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $twitch;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isTwitch;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $discord;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isDiscord;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublic;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getIsGender(): ?bool
    {
        return $this->isGender;
    }

    public function setIsGender(?bool $isGender): self
    {
        $this->isGender = $isGender;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getIsBirthDate(): ?bool
    {
        return $this->isBirthDate;
    }

    public function setIsBirthDate(?bool $isBirthDate): self
    {
        $this->isBirthDate = $isBirthDate;

        return $this;
    }

    public function getHomePage(): ?string
    {
        return $this->homePage;
    }

    public function setHomePage(?string $homePage): self
    {
        $this->homePage = $homePage;

        return $this;
    }

    public function getIsHomePage(): ?bool
    {
        return $this->isHomePage;
    }

    public function setIsHomePage(?bool $isHomePage): self
    {
        $this->isHomePage = $isHomePage;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getIsCountry(): ?bool
    {
        return $this->isCountry;
    }

    public function setIsCountry(?bool $isCountry): self
    {
        $this->isCountry = $isCountry;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(?string $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getIsJob(): ?bool
    {
        return $this->isJob;
    }

    public function setIsJob(?bool $isJob): self
    {
        $this->isJob = $isJob;

        return $this;
    }

    public function getSteam(): ?string
    {
        return $this->steam;
    }

    public function setSteam(?string $steam): self
    {
        $this->steam = $steam;

        return $this;
    }

    public function getIsSteam(): ?bool
    {
        return $this->isSteam;
    }

    public function setIsSteam(?bool $isSteam): self
    {
        $this->isSteam = $isSteam;

        return $this;
    }

    public function getMinecraft(): ?string
    {
        return $this->minecraft;
    }

    public function setMinecraft(?string $minecraft): self
    {
        $this->minecraft = $minecraft;

        return $this;
    }

    public function getIsMinecraft(): ?bool
    {
        return $this->isMinecraft;
    }

    public function setIsMinecraft(?bool $isMinecraft): self
    {
        $this->isMinecraft = $isMinecraft;

        return $this;
    }

    public function getTwitch(): ?string
    {
        return $this->twitch;
    }

    public function setTwitch(?string $twitch): self
    {
        $this->twitch = $twitch;

        return $this;
    }

    public function getIsTwitch(): ?bool
    {
        return $this->isTwitch;
    }

    public function setIsTwitch(?bool $isTwitch): self
    {
        $this->isTwitch = $isTwitch;

        return $this;
    }

    public function getDiscord(): ?string
    {
        return $this->discord;
    }

    public function setDiscord(?string $discord): self
    {
        $this->discord = $discord;

        return $this;
    }

    public function getIsDiscord(): ?bool
    {
        return $this->isDiscord;
    }

    public function setIsDiscord(?bool $isDiscord): self
    {
        $this->isDiscord = $isDiscord;

        return $this;
    }

    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

}
