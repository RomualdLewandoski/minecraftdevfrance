<?php

namespace App\Entity;

use App\Repository\LikeWallRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LikeWallRepository::class)
 */
class LikeWall
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=UserWall::class, inversedBy="likeWalls")
     * @ORM\JoinColumn(nullable=false)
     */
    private $postWall;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="likeWalls")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isLike;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isDislike;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostWall(): ?UserWall
    {
        return $this->postWall;
    }

    public function setPostWall(?UserWall $postWall): self
    {
        $this->postWall = $postWall;

        return $this;
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

    public function getIsLike(): ?bool
    {
        return $this->isLike;
    }

    public function setIsLike(?bool $isLike): self
    {
        $this->isLike = $isLike;

        return $this;
    }

    public function getIsDislike(): ?bool
    {
        return $this->isDislike;
    }

    public function setIsDislike(?bool $isDislike): self
    {
        $this->isDislike = $isDislike;

        return $this;
    }
}
