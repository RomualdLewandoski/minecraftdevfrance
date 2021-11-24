<?php

namespace App\Entity;

use App\Repository\ReportWallRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReportWallRepository::class)
 */
class ReportWall
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reportWalls")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=UserWall::class, inversedBy="reportWalls")
     * @ORM\JoinColumn(nullable=false)
     */
    private $wall;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     */
    private $wallText;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSanction;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getWall(): ?UserWall
    {
        return $this->wall;
    }

    public function setWall(?UserWall $wall): self
    {
        $this->wall = $wall;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getWallText(): ?string
    {
        return $this->wallText;
    }

    public function setWallText(string $wallText): self
    {
        $this->wallText = $wallText;

        return $this;
    }

    public function getIsSanction(): ?bool
    {
        return $this->isSanction;
    }

    public function setIsSanction(bool $isSanction): self
    {
        $this->isSanction = $isSanction;

        return $this;
    }
}
