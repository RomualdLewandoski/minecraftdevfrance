<?php

namespace App\Entity;

use App\Repository\ReportReplyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReportReplyRepository::class)
 */
class ReportReply
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reportReplies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=Reply::class, inversedBy="reportReplies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reply;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     */
    private $replyContent;

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

    public function getReply(): ?Reply
    {
        return $this->reply;
    }

    public function setReply(?Reply $reply): self
    {
        $this->reply = $reply;

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

    public function getReplyContent(): ?string
    {
        return $this->replyContent;
    }

    public function setReplyContent(string $replyContent): self
    {
        $this->replyContent = $replyContent;

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
