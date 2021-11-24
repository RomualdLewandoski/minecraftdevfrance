<?php

namespace App\Entity;

use App\Repository\ForumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @ORM\Entity(repositoryClass=ForumRepository::class)
 */
class Forum
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
    private $nom;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isLocked;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity=CatForum::class, inversedBy="forums")
     */
    private $CatForum;

    /**
     * @ORM\ManyToOne(targetEntity=Forum::class, inversedBy="forums")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Forum::class, mappedBy="parent")
     */
    private $forums;

    /**
     * @ORM\OneToMany(targetEntity=Topic::class, mappedBy="forum",  cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"lastReplyAt" = "DESC"})
     */
    private $topics;

    /**
     * @ORM\OneToMany(targetEntity=Brouillon::class, mappedBy="forum", orphanRemoval=true)
     */
    private $brouillons;


    public function __construct()
    {
        $this->forums = new ArrayCollection();
        $this->topics = new ArrayCollection();
        $this->brouillons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCatForum(): ?CatForum
    {
        return $this->CatForum;
    }

    public function setCatForum(?CatForum $CatForum): self
    {
        $this->CatForum = $CatForum;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }


    /**
     * @return Collection|self[]
     */
    public function getForums(): Collection
    {
        return $this->forums;
    }

    public function getCatName()
    {
        return $this->getCatForum() ? $this->getCatForum()->getName() : "Sous-Forums";
    }

    public function frontDisplayName()
    {
        $str = "";
        if ($this->getCatForum()) {
            $str = $str . $this->getCatForum()->getName() . " - ";
        }
        if ($this->getParent() != null) {
            $str = $str . $this->getParent()->frontDisplayName() . " <i class='fas fa-arrow-right'></i> ";
        }

        $str = $str . $this->getNom();

        return $str;
    }

    public function getDisplayName()
    {
        $str = "";
        if ($this->getCatForum()) {
            $str = $str . "|" . $this->getCatForum()->getName() . "| - ";
        }
        if ($this->getParent() != null) {
            $str = $str . $this->getParent()->getDisplayName() . " -> ";
        }

        $str = $str . $this->getNom();

        return $str;
    }

    public function addForum(self $forum): self
    {
        if (!$this->forums->contains($forum)) {
            $this->forums[] = $forum;
            $forum->setParent($this);
        }

        return $this;
    }

    public function removeForum(self $forum): self
    {
        if ($this->forums->contains($forum)) {
            $this->forums->removeElement($forum);
            // set the owning side to null (unless already changed)
            if ($forum->getParent() === $this) {
                $forum->setParent(null);
            }
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

    public function getPined()
    {
        $arr = [];
        $topics = $this->topics;
        foreach ($topics as $topic) {
            if ($topic->getIsPined()) {
                array_push($arr, $topic);
            }
        }
        return count($arr) > 0 ? $arr : null;
    }

    public function getNonPined(){
        $arr = [];
        $topics = $this->topics;
        foreach ($topics as $topic) {
            if (!$topic->getIsPined()) {
                array_push($arr, $topic);
            }
        }
        return count($arr) > 0 ? $arr : null;
    }

    public function getLastTopic()
    {
        $topics = $this->topics;
        if ($topics == null) {
            return null;
        } else {
            return $topics[0];
        }
    }

    public function addTopic(Topic $topic): self
    {
        if (!$this->topics->contains($topic)) {
            $this->topics[] = $topic;
            $topic->setForum($this);
        }

        return $this;
    }

    public function removeTopic(Topic $topic): self
    {
        if ($this->topics->contains($topic)) {
            $this->topics->removeElement($topic);
            // set the owning side to null (unless already changed)
            if ($topic->getForum() === $this) {
                $topic->setForum(null);
            }
        }

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
            $brouillon->setForum($this);
        }

        return $this;
    }

    public function removeBrouillon(Brouillon $brouillon): self
    {
        if ($this->brouillons->contains($brouillon)) {
            $this->brouillons->removeElement($brouillon);
            // set the owning side to null (unless already changed)
            if ($brouillon->getForum() === $this) {
                $brouillon->setForum(null);
            }
        }

        return $this;
    }


}
