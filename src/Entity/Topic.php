<?php

namespace App\Entity;

use App\Repository\TopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ORM\Entity(repositoryClass=TopicRepository::class)
 */
class Topic
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $postedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isLocked;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPined;

    /**
     * @ORM\ManyToOne(targetEntity=Forum::class, inversedBy="topics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $forum;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="topics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="topics")
     * @OrderBy({"priority" = "ASC"})
     */
    private $tags;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastReplyAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberView;

    /**
     * @ORM\OneToMany(targetEntity=Reply::class, mappedBy="topic", cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"postedAt" = "DESC"})
     */
    private $replies;

    /**
     * @ORM\OneToMany(targetEntity=LikeTopic::class, mappedBy="topic",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $likeTopics;

    /**
     * @ORM\OneToMany(targetEntity=SuperLikeTopic::class, mappedBy="topic",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $superLikeTopics;

    /**
     * @ORM\OneToOne(targetEntity=Reply::class)
     */
    private $solution;

    /**
     * @ORM\OneToMany(targetEntity=Activity::class, mappedBy="targetIdTopic",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $activities;

    /**
     * @ORM\OneToMany(targetEntity=ReportTopic::class, mappedBy="topic", orphanRemoval=true)
     */
    private $reportTopics;




    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->replies = new ArrayCollection();
        $this->likeTopics = new ArrayCollection();
        $this->superLikeTopics = new ArrayCollection();
        $this->solution = new ArrayCollection();
        $this->activities = new ArrayCollection();
        $this->reportTopics = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
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

    public function getIsLocked(): ?bool
    {
        return $this->isLocked;
    }

    public function setIsLocked(bool $isLocked): self
    {
        $this->isLocked = $isLocked;

        return $this;
    }

    public function getIsPined(): ?bool
    {
        return $this->isPined;
    }

    public function setIsPined(bool $isPined): self
    {
        $this->isPined = $isPined;

        return $this;
    }

    public function getForum(): ?Forum
    {
        return $this->forum;
    }

    public function setForum(?Forum $forum): self
    {
        $this->forum = $forum;

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

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    public function getLastReplyAt(): ?\DateTimeInterface
    {
        return $this->lastReplyAt;
    }

    public function setLastReplyAt(\DateTimeInterface $lastReplyAt): self
    {
        $this->lastReplyAt = $lastReplyAt;

        return $this;
    }

    public function getNumberView(): ?int
    {
        return $this->numberView;
    }

    public function setNumberView(int $numberView): self
    {
        $this->numberView = $numberView;

        return $this;
    }

    /**
     * @return Collection|Reply[]
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function getLastReplies()
    {
        $replies = $this->replies;
        if ($replies == null) {
            return null;
        } else {
            return $replies[0];
        }
    }

    public function addReply(Reply $reply): self
    {
        if (!$this->replies->contains($reply)) {
            $this->replies[] = $reply;
            $reply->setTopic($this);
        }

        return $this;
    }

    public function removeReply(Reply $reply): self
    {
        if ($this->replies->contains($reply)) {

            $this->replies->removeElement($reply);
            // set the owning side to null (unless already changed)
            if ($reply->getTopic() === $this) {
                $reply->setTopic(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LikeTopic[]
     */
    public function getLikeTopics(): Collection
    {
        return $this->likeTopics;
    }

    public function addLikeTopic(LikeTopic $likeTopic): self
    {
        if (!$this->likeTopics->contains($likeTopic)) {
            $this->likeTopics[] = $likeTopic;
            $likeTopic->setTopic($this);
        }

        return $this;
    }

    public function removeLikeTopic(LikeTopic $likeTopic): self
    {
        if ($this->likeTopics->contains($likeTopic)) {
            $this->likeTopics->removeElement($likeTopic);
            // set the owning side to null (unless already changed)
            if ($likeTopic->getTopic() === $this) {
                $likeTopic->setTopic(null);
            }
        }

        return $this;
    }

    public function getLikes(){
        $arr = [];
        foreach ($this->getLikeTopics() as $like){
            if ($like->getIsLike()){
                array_push($arr, $like);
            }
        }
        return $arr;
    }

    public function getDislike(){
        $arr = [];
        foreach ($this->getLikeTopics() as $like){
            if ($like->getIsDislike()){
                array_push($arr, $like);
            }
        }
        return $arr;
    }

    /**
     * @return Collection|SuperLikeTopic[]
     */
    public function getSuperLikeTopics(): Collection
    {
        return $this->superLikeTopics;
    }

    public function addSuperLikeTopic(SuperLikeTopic $superLikeTopic): self
    {
        if (!$this->superLikeTopics->contains($superLikeTopic)) {
            $this->superLikeTopics[] = $superLikeTopic;
            $superLikeTopic->setTopic($this);
        }

        return $this;
    }

    public function removeSuperLikeTopic(SuperLikeTopic $superLikeTopic): self
    {
        if ($this->superLikeTopics->contains($superLikeTopic)) {
            $this->superLikeTopics->removeElement($superLikeTopic);
            // set the owning side to null (unless already changed)
            if ($superLikeTopic->getTopic() === $this) {
                $superLikeTopic->setTopic(null);
            }
        }

        return $this;
    }

    public function getSolution(): ?Reply
    {
        return $this->solution;
    }

    public function setSolution(?Reply $solution): self
    {
        $this->solution = $solution;

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
            $activity->setTargetIdTopic($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->contains($activity)) {
            $this->activities->removeElement($activity);
            // set the owning side to null (unless already changed)
            if ($activity->getTargetIdTopic() === $this) {
                $activity->setTargetIdTopic(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ReportTopic[]
     */
    public function getReportTopics(): Collection
    {
        return $this->reportTopics;
    }

    public function addReportTopic(ReportTopic $reportTopic): self
    {
        if (!$this->reportTopics->contains($reportTopic)) {
            $this->reportTopics[] = $reportTopic;
            $reportTopic->setTopic($this);
        }

        return $this;
    }

    public function removeReportTopic(ReportTopic $reportTopic): self
    {
        if ($this->reportTopics->contains($reportTopic)) {
            $this->reportTopics->removeElement($reportTopic);
            // set the owning side to null (unless already changed)
            if ($reportTopic->getTopic() === $this) {
                $reportTopic->setTopic(null);
            }
        }

        return $this;
    }





}
