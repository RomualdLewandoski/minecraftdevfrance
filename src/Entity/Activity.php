<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActivityRepository::class)
 */
class Activity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="activities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Topic::class, inversedBy="activities")
     */
    private $targetIdTopic;

    /**
     * @ORM\ManyToOne(targetEntity=Reply::class, inversedBy="activities")
     */
    private $targetIdReply;

    /**
     * @ORM\ManyToOne(targetEntity=UserWall::class, inversedBy="activities")
     */
    private $targetIdWall;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

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

    public function getTargetIdTopic(): ?Topic
    {
        return $this->targetIdTopic;
    }

    public function setTargetIdTopic(?Topic $targetIdTopic): self
    {
        $this->targetIdTopic = $targetIdTopic;

        return $this;
    }

    public function getTargetIdReply(): ?Reply
    {
        return $this->targetIdReply;
    }

    public function setTargetIdReply(?Reply $targetIdReply): self
    {
        $this->targetIdReply = $targetIdReply;

        return $this;
    }

    public function getTargetIdWall(): ?UserWall
    {
        return $this->targetIdWall;
    }

    public function setTargetIdWall(?UserWall $targetIdWall): self
    {
        $this->targetIdWall = $targetIdWall;

        return $this;
    }
}
