<?php

namespace App\Entity;

use App\Repository\ConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConversationRepository::class)
 */
class Conversation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="conversations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\OneToMany(targetEntity=ConversationMeta::class, mappedBy="conversation", orphanRemoval=true)
     */
    private $conversationMetas;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="conversation", orphanRemoval=true)
     */
    private $messages;

    protected $em;

    public function __construct()
    {
        $this->conversationMetas = new ArrayCollection();
        $this->messages = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

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
            $conversationMeta->setConversation($this);
        }

        return $this;
    }

    public function removeConversationMeta(ConversationMeta $conversationMeta): self
    {
        if ($this->conversationMetas->contains($conversationMeta)) {
            $this->conversationMetas->removeElement($conversationMeta);
            // set the owning side to null (unless already changed)
            if ($conversationMeta->getConversation() === $this) {
                $conversationMeta->setConversation(null);
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
            $message->setConversation($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getConversation() === $this) {
                $message->setConversation(null);
            }
        }

        return $this;
    }

    public function isReadByUser(User $user)
    {
        $messages = $this->getMessages();
        $flag = true;
        foreach ($messages as $message) {
            $meta = $message->getMetaForUser($user);
            if ($meta != null){
                if (!$meta->getIsRead()){
                    $flag = false;
                    break;
                }
            }
            /*if ($message->getMetaForUser($user)->getIsRead()) {
                $flag = false;
                break;
            }*/
        }
        return $flag;
    }


    public function getLastMsg()
    {
        return $this->getMessages()->last();
    }

    public function getOtherUser(User $user){
        $metas = $this->getConversationMetas();
        $temp = null;
        foreach ($metas as $meta){
            if ($meta->getParticipants() != $user ){
                $temp = $meta->getParticipants();
            break;
            }
        }
        return $temp;
    }
}
