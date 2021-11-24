<?php

namespace App\Entity;

use App\Repository\LikeReplyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LikeReplyRepository::class)
 */
class LikeReply
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Reply::class, inversedBy="likeReplies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reply;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="likeReplies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isLike;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDislike;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReply(): ?Reply
    {
        return $this->reply;
    }

    public function setReply(?Reply $reply): self
    {
        $this->reply = $reply;

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

    public function setIsLike(bool $isLike): self
    {
        $this->isLike = $isLike;

        return $this;
    }

    public function getIsDislike(): ?bool
    {
        return $this->isDislike;
    }

    public function setIsDislike(bool $isDislike): self
    {
        $this->isDislike = $isDislike;

        return $this;
    }
}
