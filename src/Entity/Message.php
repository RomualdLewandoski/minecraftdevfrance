<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Conversation::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $conversation;


    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $postedAt;

    /**
     * @ORM\OneToMany(targetEntity=MessageMeta::class, mappedBy="message", orphanRemoval=true)
     */
    private $messageMetas;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    public function __construct()
    {
        $this->messageMetas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }





    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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
            $messageMeta->setMessage($this);
        }

        return $this;
    }

    public function removeMessageMeta(MessageMeta $messageMeta): self
    {
        if ($this->messageMetas->contains($messageMeta)) {
            $this->messageMetas->removeElement($messageMeta);
            // set the owning side to null (unless already changed)
            if ($messageMeta->getMessage() === $this) {
                $messageMeta->setMessage(null);
            }
        }

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

    public function getMetaForUser(User $user){
        $temp = null;
        foreach ($this->getMessageMetas() as $meta){
            if ($meta->getUser() == $user){
                $temp = $meta;
                break;
            }
        }
        return $temp;
    }

}
