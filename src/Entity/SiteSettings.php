<?php

namespace App\Entity;

use App\Repository\SiteSettingsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SiteSettingsRepository::class)
 */
class SiteSettings
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $cgu;

    /**
     * @ORM\Column(type="text")
     */
    private $rgpd;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $youtube;

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCgu(): ?string
    {
        return $this->cgu;
    }

    public function setCgu(string $cgu): self
    {
        $this->cgu = $cgu;

        return $this;
    }

    public function getRgpd(): ?string
    {
        return $this->rgpd;
    }

    public function setRgpd(string $rgpd): self
    {
        $this->rgpd = $rgpd;

        return $this;
    }

    public function getYoutube(): ?string
    {
        return $this->youtube;
    }

    public function setYoutube(?string $youtube): self
    {
        $this->youtube = $youtube;

        return $this;
    }

}
