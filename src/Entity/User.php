<?php

namespace App\Entity;

use App\Repository\UserRankRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $LastLogin;

    /**
     * @ORM\OneToMany(targetEntity=UserWall::class, mappedBy="User")
     */
    private $userWalls;

    /**
     * @ORM\OneToOne(targetEntity=UserInfo::class, mappedBy="User", cascade={"persist", "remove"})
     */
    private $userInfo;

    /**
     * @ORM\OneToMany(targetEntity=LikeWall::class, mappedBy="author")
     */
    private $likeWalls;

    /**
     * @ORM\ManyToMany(targetEntity=UserRank::class, inversedBy="users")
     * @OrderBy({"Priority" = "ASC"})
     */
    private $UserRanks;

    /**
     * @ORM\OneToMany(targetEntity=Topic::class, mappedBy="author")
     * @OrderBy({"postedAt" = "ASC"})
     */
    private $topics;

    /**
     * @ORM\OneToMany(targetEntity=Reply::class, mappedBy="author")
     */
    private $replies;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalLike;

    /**
     * @ORM\OneToMany(targetEntity=LikeTopic::class, mappedBy="author", orphanRemoval=true)
     */
    private $likeTopics;

    /**
     * @ORM\OneToMany(targetEntity=SuperLikeTopic::class, mappedBy="author", orphanRemoval=true)
     */
    private $superLikeTopics;

    /**
     * @ORM\OneToMany(targetEntity=LikeReply::class, mappedBy="author", orphanRemoval=true)
     */
    private $likeReplies;

    /**
     * @ORM\OneToMany(targetEntity=SuperLikeReply::class, mappedBy="author", orphanRemoval=true)
     */
    private $superLikeReplies;

    /**
     * @ORM\OneToMany(targetEntity=Activity::class, mappedBy="author", orphanRemoval=true)
     * @OrderBy({"date" = "DESC"})
     */
    private $activities;

    /**
     * @ORM\OneToMany(targetEntity=UserTrophy::class, mappedBy="user", orphanRemoval=true)
     */
    private $userTrophies;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isKonami;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDoom;

    /**
     * @ORM\Column(type="boolean")
     */
    private $useDoomFont;

    /**
     * @ORM\OneToMany(targetEntity=Cookie::class, mappedBy="user", orphanRemoval=true)
     */
    private $cookies;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCookieMaster;

    /**
     * @ORM\OneToMany(targetEntity=ReportTopic::class, mappedBy="author", orphanRemoval=true)
     */
    private $reportTopics;

    /**
     * @ORM\OneToMany(targetEntity=ReportReply::class, mappedBy="author", orphanRemoval=true)
     */
    private $reportReplies;

    /**
     * @ORM\OneToMany(targetEntity=ReportWall::class, mappedBy="author", orphanRemoval=true)
     */
    private $reportWalls;

    /**
     * @ORM\OneToMany(targetEntity=ReportUser::class, mappedBy="author", orphanRemoval=true)
     */
    private $reportUsers;

    /**
     * @ORM\OneToMany(targetEntity=ReportUser::class, mappedBy="user", orphanRemoval=true)
     */
    private $reportedUsers;

    /**
     * @ORM\OneToMany(targetEntity=SystemMessage::class, mappedBy="target", orphanRemoval=true)
     */
    private $systemMessages;

    /**
     * @ORM\OneToMany(targetEntity=Conversation::class, mappedBy="createdBy", orphanRemoval=true)
     */
    private $conversations;

    /**
     * @ORM\OneToMany(targetEntity=ConversationMeta::class, mappedBy="participants", orphanRemoval=true)
     */
    private $conversationMetas;


    /**
     * @ORM\OneToMany(targetEntity=MessageMeta::class, mappedBy="user", orphanRemoval=true)
     */
    private $messageMetas;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="author", orphanRemoval=true)
     */
    private $messages;

    /**
     * @ORM\Column(type="text")
     */
    private $lastActive;

    /**
     * @ORM\Column(type="integer")
     */
    private $warns;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $releasedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBanned;

    /**
     * @ORM\Column(type="integer")
     */
    private $superLikeCount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $signature;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isMinecraftAvatar;

    /**
     * @ORM\OneToMany(targetEntity=Brouillon::class, mappedBy="author", orphanRemoval=true)
     */
    private $brouillons;

    public function __construct()
    {
        $this->userWalls = new ArrayCollection();
        $this->likeWalls = new ArrayCollection();
        $this->UserRanks = new ArrayCollection();
        $this->topics = new ArrayCollection();
        $this->replies = new ArrayCollection();
        $this->likeTopics = new ArrayCollection();
        $this->superLikeTopics = new ArrayCollection();
        $this->likeReplies = new ArrayCollection();
        $this->superLikeReplies = new ArrayCollection();
        $this->activities = new ArrayCollection();
        $this->userTrophies = new ArrayCollection();
        $this->cookies = new ArrayCollection();
        $this->reportTopics = new ArrayCollection();
        $this->reportReplies = new ArrayCollection();
        $this->reportWalls = new ArrayCollection();
        $this->reportUsers = new ArrayCollection();
        $this->reportedUsers = new ArrayCollection();
        $this->systemMessages = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->conversationMetas = new ArrayCollection();
        $this->messageMetas = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->brouillons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {

        return (string)$this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->LastLogin;
    }

    public function setLastLogin(\DateTimeInterface $LastLogin): self
    {
        $this->LastLogin = $LastLogin;

        return $this;
    }

    /**
     * @return Collection|UserWall[]
     */
    public function getUserWalls(): Collection
    {
        return $this->userWalls;
    }

    public function addUserWall(UserWall $userWall): self
    {
        if (!$this->userWalls->contains($userWall)) {
            $this->userWalls[] = $userWall;
            $userWall->setUser($this);
        }

        return $this;
    }

    public function removeUserWall(UserWall $userWall): self
    {
        if ($this->userWalls->contains($userWall)) {
            $this->userWalls->removeElement($userWall);
            // set the owning side to null (unless already changed)
            if ($userWall->getUser() === $this) {
                $userWall->setUser(null);
            }
        }

        return $this;
    }

    public function getUserInfo(): ?UserInfo
    {
        return $this->userInfo;
    }

    public function setUserInfo(UserInfo $userInfo): self
    {
        $this->userInfo = $userInfo;

        // set the owning side of the relation if necessary
        if ($userInfo->getUser() !== $this) {
            $userInfo->setUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|LikeWall[]
     */
    public function getLikeWalls(): Collection
    {
        return $this->likeWalls;
    }

    public function addLikeWall(LikeWall $likeWall): self
    {
        if (!$this->likeWalls->contains($likeWall)) {
            $this->likeWalls[] = $likeWall;
            $likeWall->setAuthor($this);
        }

        return $this;
    }

    public function removeLikeWall(LikeWall $likeWall): self
    {
        if ($this->likeWalls->contains($likeWall)) {
            $this->likeWalls->removeElement($likeWall);
            // set the owning side to null (unless already changed)
            if ($likeWall->getAuthor() === $this) {
                $likeWall->setAuthor(null);
            }
        }

        return $this;
    }

    public function isLiked(UserWall $userWall)
    {
        $flag = false;
        $likes = $this->getLikeWalls();
        foreach ($likes as $like) {
            if ($like->getPostWall() == $userWall) {
                if ($like->getIsLike()) {
                    $flag = true;
                    break;
                }
            }
        }
        return $flag;
    }

    public function isDisliked(UserWall $userWall)
    {
        $flag = false;
        $likes = $this->getLikeWalls();
        foreach ($likes as $like) {
            if ($like->getPostWall() == $userWall) {
                if ($like->getIsDislike()) {
                    $flag = true;
                    break;
                }
            }
        }
        return $flag;
    }

    public function isLikedTopic(Topic $topic)
    {
        $flag = false;
        $likes = $this->getLikeTopics();
        foreach ($likes as $like) {
            if ($like->getTopic() == $topic) {
                if ($like->getIsLike()) {
                    $flag = true;
                    break;
                }
            }
        }
        return $flag;
    }

    public function isDislikedTopic(Topic $topic)
    {
        $flag = false;
        $likes = $this->getLikeTopics();
        foreach ($likes as $like) {
            if ($like->getTopic() == $topic) {
                if ($like->getIsDislike()) {
                    $flag = true;
                    break;
                }
            }
        }
        return $flag;
    }

    public function isLikedreply(reply $reply)
    {
        $flag = false;
        $likes = $this->getLikeReplies();
        foreach ($likes as $like) {
            if ($like->getReply() == $reply) {
                if ($like->getIsLike()) {
                    $flag = true;
                    break;
                }
            }
        }
        return $flag;
    }

    public function isDislikedReply(Reply $reply)
    {
        $flag = false;
        $likes = $this->getLikeReplies();
        foreach ($likes as $like) {
            if ($like->getReply() == $reply) {
                if ($like->getIsDislike()) {
                    $flag = true;
                    break;
                }
            }
        }
        return $flag;
    }


    public function isSuperLikeTopic(Topic $topic)
    {
        $flag = false;
        $likes = $this->getSuperLikeTopics();
        foreach ($likes as $like) {
            if ($like->getTopic() == $topic) {
                $flag = true;
                break;
            }
        }
        return $flag;
    }


    public function isSuperLikeReply(Reply $reply)
    {
        $flag = false;
        $likes = $this->getSuperLikeReplies();
        foreach ($likes as $like) {
            if ($like->getReply() == $reply) {
                $flag = true;
                break;
            }
        }
        return $flag;
    }


    /**
     * @return Collection|UserRank[]
     */
    public function getUserRanks(): Collection
    {

        return $this->UserRanks;
    }

    public function addUserRank(UserRank $userRank): self
    {
        if (!$this->UserRanks->contains($userRank)) {
            $this->UserRanks[] = $userRank;
        }

        return $this;
    }

    public function removeUserRank(UserRank $userRank): self
    {
        if ($this->UserRanks->contains($userRank)) {
            $this->UserRanks->removeElement($userRank);
        }

        return $this;
    }

    /**
     * @return Collection|Topic[]
     */
    public function getTopics(): Collection
    {
        return $this->topics;
    }

    public function addTopic(Topic $topic): self
    {
        if (!$this->topics->contains($topic)) {
            $this->topics[] = $topic;
            $topic->setAuthor($this);
        }

        return $this;
    }

    public function removeTopic(Topic $topic): self
    {
        if ($this->topics->contains($topic)) {
            $this->topics->removeElement($topic);
            // set the owning side to null (unless already changed)
            if ($topic->getAuthor() === $this) {
                $topic->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reply[]
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReply(Reply $reply): self
    {
        if (!$this->replies->contains($reply)) {
            $this->replies[] = $reply;
            $reply->setAuthor($this);
        }

        return $this;
    }

    public function removeReply(Reply $reply): self
    {
        if ($this->replies->contains($reply)) {
            $this->replies->removeElement($reply);
            // set the owning side to null (unless already changed)
            if ($reply->getAuthor() === $this) {
                $reply->setAuthor(null);
            }
        }

        return $this;
    }

    public function getTotalLike(): ?int
    {
        return $this->totalLike;
    }

    public function setTotalLike(int $totalLike): self
    {
        $this->totalLike = $totalLike;

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
            $likeTopic->setAuthor($this);
        }

        return $this;
    }

    public function removeLikeTopic(LikeTopic $likeTopic): self
    {
        if ($this->likeTopics->contains($likeTopic)) {
            $this->likeTopics->removeElement($likeTopic);
            // set the owning side to null (unless already changed)
            if ($likeTopic->getAuthor() === $this) {
                $likeTopic->setAuthor(null);
            }
        }

        return $this;
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
            $superLikeTopic->setAuthor($this);
        }

        return $this;
    }

    public function removeSuperLikeTopic(SuperLikeTopic $superLikeTopic): self
    {
        if ($this->superLikeTopics->contains($superLikeTopic)) {
            $this->superLikeTopics->removeElement($superLikeTopic);
            // set the owning side to null (unless already changed)
            if ($superLikeTopic->getAuthor() === $this) {
                $superLikeTopic->setAuthor(null);
            }
        }

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
            $likeReply->setAuthor($this);
        }

        return $this;
    }

    public function removeLikeReply(LikeReply $likeReply): self
    {
        if ($this->likeReplies->contains($likeReply)) {
            $this->likeReplies->removeElement($likeReply);
            // set the owning side to null (unless already changed)
            if ($likeReply->getAuthor() === $this) {
                $likeReply->setAuthor(null);
            }
        }

        return $this;
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
            $superLikeReply->setAuthor($this);
        }

        return $this;
    }

    public function removeSuperLikeReply(SuperLikeReply $superLikeReply): self
    {
        if ($this->superLikeReplies->contains($superLikeReply)) {
            $this->superLikeReplies->removeElement($superLikeReply);
            // set the owning side to null (unless already changed)
            if ($superLikeReply->getAuthor() === $this) {
                $superLikeReply->setAuthor(null);
            }
        }

        return $this;
    }


    public function countGivenLikes()
    {
        $count = 0;
        foreach ($this->getLikeTopics() as $topicLike) {
            if ($topicLike->getIsLike()) {
                $count++;
            }
        }
        foreach ($this->getLikeReplies() as $likeReply) {
            if ($likeReply->getIsLike()) {
                $count++;
            }
        }
        foreach ($this->getLikeWalls() as $likeWall) {
            if ($likeWall->getIsLike()) {
                $count++;
            }
        }
        return $count;
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
            $activity->setAuthor($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->contains($activity)) {
            $this->activities->removeElement($activity);
            // set the owning side to null (unless already changed)
            if ($activity->getAuthor() === $this) {
                $activity->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserTrophy[]
     */
    public function getUserTrophies(): Collection
    {
        return $this->userTrophies;
    }

    public function hasTrophy(Trophy $trophy)
    {
        $flag = false;
        foreach ($this->getUserTrophies() as $userTrophy) {
            if ($userTrophy->getTrophy() == $trophy) {
                $flag = true;
                break;
            }
        }
        return $flag;
    }

    public function addUserTrophy(UserTrophy $userTrophy): self
    {
        if (!$this->userTrophies->contains($userTrophy)) {
            $this->userTrophies[] = $userTrophy;
            $userTrophy->setUser($this);
        }

        return $this;
    }

    public function removeUserTrophy(UserTrophy $userTrophy): self
    {
        if ($this->userTrophies->contains($userTrophy)) {
            $this->userTrophies->removeElement($userTrophy);
            // set the owning side to null (unless already changed)
            if ($userTrophy->getUser() === $this) {
                $userTrophy->setUser(null);
            }
        }

        return $this;
    }

    public function getIsKonami(): ?bool
    {
        return $this->isKonami;
    }

    public function setIsKonami(bool $isKonami): self
    {
        $this->isKonami = $isKonami;

        return $this;
    }

    public function getIsDoom(): ?bool
    {
        return $this->isDoom;
    }

    public function setIsDoom(bool $isDoom): self
    {
        $this->isDoom = $isDoom;

        return $this;
    }

    public function getUseDoomFont(): ?bool
    {
        return $this->useDoomFont;
    }

    public function setUseDoomFont(bool $useDoomFont): self
    {
        $this->useDoomFont = $useDoomFont;

        return $this;
    }

    /**
     * @return Collection|Cookie[]
     */
    public function getCookies(): Collection
    {
        return $this->cookies;
    }

    public function addCookie(Cookie $cookie): self
    {
        if (!$this->cookies->contains($cookie)) {
            $this->cookies[] = $cookie;
            $cookie->setUser($this);
        }

        return $this;
    }

    public function removeCookie(Cookie $cookie): self
    {
        if ($this->cookies->contains($cookie)) {
            $this->cookies->removeElement($cookie);
            // set the owning side to null (unless already changed)
            if ($cookie->getUser() === $this) {
                $cookie->setUser(null);
            }
        }

        return $this;
    }

    public function getIsCookieMaster(): ?bool
    {
        return $this->isCookieMaster;
    }

    public function setIsCookieMaster(bool $isCookieMaster): self
    {
        $this->isCookieMaster = $isCookieMaster;

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
            $reportTopic->setAuthor($this);
        }

        return $this;
    }

    public function removeReportTopic(ReportTopic $reportTopic): self
    {
        if ($this->reportTopics->contains($reportTopic)) {
            $this->reportTopics->removeElement($reportTopic);
            // set the owning side to null (unless already changed)
            if ($reportTopic->getAuthor() === $this) {
                $reportTopic->setAuthor(null);
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
            $reportReply->setAuthor($this);
        }

        return $this;
    }

    public function removeReportReply(ReportReply $reportReply): self
    {
        if ($this->reportReplies->contains($reportReply)) {
            $this->reportReplies->removeElement($reportReply);
            // set the owning side to null (unless already changed)
            if ($reportReply->getAuthor() === $this) {
                $reportReply->setAuthor(null);
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
            $reportWall->setAuthor($this);
        }

        return $this;
    }

    public function removeReportWall(ReportWall $reportWall): self
    {
        if ($this->reportWalls->contains($reportWall)) {
            $this->reportWalls->removeElement($reportWall);
            // set the owning side to null (unless already changed)
            if ($reportWall->getAuthor() === $this) {
                $reportWall->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ReportUser[]
     */
    public function getReportUsers(): Collection
    {
        return $this->reportUsers;
    }

    public function addReportUser(ReportUser $reportUser): self
    {
        if (!$this->reportUsers->contains($reportUser)) {
            $this->reportUsers[] = $reportUser;
            $reportUser->setAuthor($this);
        }

        return $this;
    }

    public function removeReportUser(ReportUser $reportUser): self
    {
        if ($this->reportUsers->contains($reportUser)) {
            $this->reportUsers->removeElement($reportUser);
            // set the owning side to null (unless already changed)
            if ($reportUser->getAuthor() === $this) {
                $reportUser->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ReportUser[]
     */
    public function getReportedUsers(): Collection
    {
        return $this->reportedUsers;
    }

    public function addReportedUser(ReportUser $reportUser): self
    {
        if (!$this->reportedUsers->contains($reportUser)) {
            $this->reportUsers[] = $reportUser;
            $reportUser->setUser($this);
        }

        return $this;
    }

    public function removeReportedUser(ReportUser $reportUser): self
    {
        if ($this->reportedUsers->contains($reportUser)) {
            $this->reportedUsers->removeElement($reportUser);
            // set the owning side to null (unless already changed)
            if ($reportUser->getUser() === $this) {
                $reportUser->setUser(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|SystemMessage[]
     */
    public function getSystemMessages(): Collection
    {
        return $this->systemMessages;
    }

    public function addSystemMessage(SystemMessage $systemMessage): self
    {
        if (!$this->systemMessages->contains($systemMessage)) {
            $this->systemMessages[] = $systemMessage;
            $systemMessage->setTarget($this);
        }

        return $this;
    }

    public function removeSystemMessage(SystemMessage $systemMessage): self
    {
        if ($this->systemMessages->contains($systemMessage)) {
            $this->systemMessages->removeElement($systemMessage);
            // set the owning side to null (unless already changed)
            if ($systemMessage->getTarget() === $this) {
                $systemMessage->setTarget(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Conversation[]
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
            $conversation->setCreatedBy($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->contains($conversation)) {
            $this->conversations->removeElement($conversation);
            // set the owning side to null (unless already changed)
            if ($conversation->getCreatedBy() === $this) {
                $conversation->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConversationMeta[]
     */
    public function getConversationMetas(): Collection
    {
        return $this->conversationMetas;
    }

    public function addConversationMeta(ConversationMeta $conversationMeta): self
    {
        if (!$this->conversationMetas->contains($conversationMeta)) {
            $this->conversationMetas[] = $conversationMeta;
            $conversationMeta->setParticipants($this);
        }

        return $this;
    }

    public function removeConversationMeta(ConversationMeta $conversationMeta): self
    {
        if ($this->conversationMetas->contains($conversationMeta)) {
            $this->conversationMetas->removeElement($conversationMeta);
            // set the owning side to null (unless already changed)
            if ($conversationMeta->getParticipants() === $this) {
                $conversationMeta->setParticipants(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|MessageMeta[]
     */
    public function getMessageMetas(): Collection
    {
        return $this->messageMetas;
    }

    public function addMessageMeta(MessageMeta $messageMeta): self
    {
        if (!$this->messageMetas->contains($messageMeta)) {
            $this->messageMetas[] = $messageMeta;
            $messageMeta->setUser($this);
        }

        return $this;
    }

    public function removeMessageMeta(MessageMeta $messageMeta): self
    {
        if ($this->messageMetas->contains($messageMeta)) {
            $this->messageMetas->removeElement($messageMeta);
            // set the owning side to null (unless already changed)
            if ($messageMeta->getUser() === $this) {
                $messageMeta->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setAuthor($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getAuthor() === $this) {
                $message->setAuthor(null);
            }
        }

        return $this;
    }

    public function getLastActive(): ?string
    {
        return $this->lastActive;
    }

    public function setLastActive(string $lastActive): self
    {
        $this->lastActive = $lastActive;

        return $this;
    }

    public function isOnline()
    {
        return $this->getLastActive() >= time() - 20;
    }

    public function isParticipant(Conversation $conversation): bool
    {
        $flag = false;
        foreach ($this->getConversationMetas() as $conversationMeta) {
            if ($conversationMeta->getConversation() == $conversation) {
                $flag = true;
                break;
            }
        }
        return $flag;
    }

    public function getWarns(): ?int
    {
        return $this->warns;
    }

    public function setWarns(int $warns): self
    {
        $this->warns = $warns;

        return $this;
    }

    public function getReleasedAt(): ?\DateTimeInterface
    {
        return $this->releasedAt;
    }

    public function setReleasedAt(?\DateTimeInterface $releasedAt): self
    {
        $this->releasedAt = $releasedAt;

        return $this;
    }

    public function getIsBanned(): ?bool
    {
        return $this->isBanned;
    }

    public function setIsBanned(bool $isBanned): self
    {
        $this->isBanned = $isBanned;

        return $this;
    }

    public function getSuperLikeCount(): ?int
    {
        return $this->superLikeCount;
    }

    public function setSuperLikeCount(int $superLikeCount): self
    {
        $this->superLikeCount = $superLikeCount;

        return $this;
    }

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function setSignature(?string $signature): self
    {
        $this->signature = $signature;

        return $this;
    }

    public function getIsMinecraftAvatar(): ?bool
    {
        return $this->isMinecraftAvatar;
    }

    public function setIsMinecraftAvatar(bool $isMinecraftAvatar): self
    {
        $this->isMinecraftAvatar = $isMinecraftAvatar;

        return $this;
    }

    /**
     * @return Collection|Brouillon[]
     */
    public function getBrouillons(): Collection
    {
        return $this->brouillons;
    }

    public function addBrouillon(Brouillon $brouillon): self
    {
        if (!$this->brouillons->contains($brouillon)) {
            $this->brouillons[] = $brouillon;
            $brouillon->setAuthor($this);
        }

        return $this;
    }

    public function removeBrouillon(Brouillon $brouillon): self
    {
        if ($this->brouillons->contains($brouillon)) {
            $this->brouillons->removeElement($brouillon);
            // set the owning side to null (unless already changed)
            if ($brouillon->getAuthor() === $this) {
                $brouillon->setAuthor(null);
            }
        }

        return $this;
    }

}
