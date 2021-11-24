<?php

namespace App\Entity;

use App\Repository\ReplyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReplyRepository::class)
 */
class Reply
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
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $postedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="replies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=Topic::class, inversedBy="replies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $topic;

    /**
     * @ORM\OneToMany(targetEntity=LikeReply::class, mappedBy="reply", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $likeReplies;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\OneToMany(targetEntity=SuperLikeReply::class, mappedBy="reply", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $superLikeReplies;

    /**
     * @ORM\OneToMany(targetEntity=Activity::class, mappedBy="targetIdReply", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $activities;

    /**
     * @ORM\OneToMany(targetEntity=ReportReply::class, mappedBy="reply", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $reportReplies;



    public function __construct()
    {
        $this->likeReplies = new ArrayCollection();
        $this->superLikeReplies = new ArrayCollection();
        $this->activities = new ArrayCollection();
        $this->reportReplies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getPostedAt(): ?\DateTimeInterface
    {
        return $this->postedAt;
    }

    public function setPostedAt(\DateTimeInterface $postedAt): self
    {
        $this->postedAt = $postedAt;

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

    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return Collection|LikeReply[]
     */
    public function getLikeReplies(): Collection
    {
        return $this->likeReplies;
    }

    public function addLikeReply(LikeReply $likeReply): self
    {
        if (!$this->likeReplies->contains($likeReply)) {
            $this->likeReplies[] = $likeReply;
            $likeReply->setReply($this);
        }

        return $this;
    }

    public function removeLikeReply(LikeReply $likeReply): self
    {
        if ($this->likeReplies->contains($likeReply)) {
            $this->likeReplies->removeElement($likeReply);
            // set the owning side to null (unless already changed)
            if ($likeReply->getReply() === $this) {
                $likeReply->setReply(null);
            }
        }

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getLikes(){
        $arr = [];
        foreach ($this->getLikeReplies() as $like){
            if ($like->getIsLike()){
                array_push($arr, $like);
            }
        }
        return $arr;
    }

    public function getDislike(){
        $arr = [];
        foreach ($this->getLikeReplies() as $like){
            if ($like->getIsDislike()){
                array_push($arr, $like);
            }
        }
        return $arr;
    }

    /**
     * @return Collection|SuperLikeReply[]
     */
    public function getSuperLikeReplies(): Collection
    {
        return $this->superLikeReplies;
    }

    public function addSuperLikeReply(SuperLikeReply $superLikeReply): self
    {
        if (!$this->superLikeReplies->contains($superLikeReply)) {
            $this->superLikeReplies[] = $superLikeReply;
            $superLikeReply->setReply($this);
        }

        return $this;
    }

    public function removeSuperLikeReply(SuperLikeReply $superLikeReply): self
    {
        if ($this->superLikeReplies->contains($superLikeReply)) {
            $this->superLikeReplies->removeElement($superLikeReply);
            // set the owning side to null (unless already changed)
            if ($superLikeReply->getReply() === $this) {
                $superLikeReply->setReply(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Activity[]
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities[] = $activity;
            $activity->setTargetIdReply($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->contains($activity)) {
            $this->activities->removeElement($activity);
            // set the owning side to null (unless already changed)
            if ($activity->getTargetIdReply() === $this) {
                $activity->setTargetIdReply(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ReportReply[]
     */
    public function getReportReplies(): Collection
    {
        return $this->reportReplies;
    }

    public function addReportReply(ReportReply $reportReply): self
    {
        if (!$this->reportReplies->contains($reportReply)) {
            $this->reportReplies[] = $reportReply;
            $reportReply->setReply($this);
        }

        return $this;
    }

    public function removeReportReply(ReportReply $reportReply): self
    {
        if ($this->reportReplies->contains($reportReply)) {
            $this->reportReplies->removeElement($reportReply);
            // set the owning side to null (unless already changed)
            if ($reportReply->getReply() === $this) {
                $reportReply->setReply(null);
            }
        }

        return $this;
    }


}
