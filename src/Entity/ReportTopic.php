<?php

namespace App\Entity;

use App\Repository\ReportTopicRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReportTopicRepository::class)
 */
class ReportTopic
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reportTopics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=Topic::class, inversedBy="reportTopics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $topic;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     */
    private $topicContent;

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

    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): self
    {
        $this->topic = $topic;

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

    public function getTopicContent(): ?string
    {
        return $this->topicContent;
    }

    public function setTopicContent(string $topicContent): self
    {
        $this->topicContent = $topicContent;

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
