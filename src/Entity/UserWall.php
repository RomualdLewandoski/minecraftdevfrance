<?php

namespace App\Entity;

use App\Repository\UserWallRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserWallRepository::class)
 */
class UserWall
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
    private $text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $postedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userWalls")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\OneToMany(targetEntity=LikeWall::class, mappedBy="postWall", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $likeWalls;

    /**
     * @ORM\OneToMany(targetEntity=Activity::class, mappedBy="targetIdWall", orphanRemoval=true)
     */
    private $activities;

    /**
     * @ORM\OneToMany(targetEntity=ReportWall::class, mappedBy="wall", orphanRemoval=true)
     */
    private $reportWalls;

    public function __construct()
    {
        $this->likeWalls = new ArrayCollection();
        $this->activities = new ArrayCollection();
        $this->reportWalls = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

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

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    /**
     * @return Collection|LikeWall[]
     */
    public function getLikeWalls(): Collection
    {
        return $this->likeWalls;
    }

    public function getLiked(): array
    {
        $arr = [];
        $likes = $this->getLikeWalls();
        foreach ($likes as $like) {
            if ($like->getIsLike()) {
                array_push($arr, $like);
            }
        }
        return $arr;
    }

    public function getDisliked(): array
    {
        $arr = [];
        $likes = $this->getLikeWalls();
        foreach ($likes as $like) {
            if ($like->getIsDislike()) {
                array_push($arr, $like);
            }
        }
        return $arr;
    }


    public function addLikeWall(LikeWall $likeWall): self
    {
        if (!$this->likeWalls->contains($likeWall)) {
            $this->likeWalls[] = $likeWall;
            $likeWall->setPostWall($this);
        }

        return $this;
    }

    public function removeLikeWall(LikeWall $likeWall): self
    {
        if ($this->likeWalls->contains($likeWall)) {
            $this->likeWalls->removeElement($likeWall);
            // set the owning side to null (unless already changed)
            if ($likeWall->getPostWall() === $this) {
                $likeWall->setPostWall(null);
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
            $activity->setTargetIdWall($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->contains($activity)) {
            $this->activities->removeElement($activity);
            // set the owning side to null (unless already changed)
            if ($activity->getTargetIdWall() === $this) {
                $activity->setTargetIdWall(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ReportWall[]
     */
    public function getReportWalls(): Collection
    {
        return $this->reportWalls;
    }

    public function addReportWall(ReportWall $reportWall): self
    {
        if (!$this->reportWalls->contains($reportWall)) {
            $this->reportWalls[] = $reportWall;
            $reportWall->setWall($this);
        }

        return $this;
    }

    public function removeReportWall(ReportWall $reportWall): self
    {
        if ($this->reportWalls->contains($reportWall)) {
            $this->reportWalls->removeElement($reportWall);
            // set the owning side to null (unless already changed)
            if ($reportWall->getWall() === $this) {
                $reportWall->setWall(null);
            }
        }

        return $this;
    }
}
