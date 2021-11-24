<?php

namespace App\Entity;

use App\Repository\ConversationMetaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConversationMetaRepository::class)
 */
class ConversationMeta
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Conversation::class, inversedBy="conversationMetas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $conversation;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="conversationMetas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $participants;

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

    public function getParticipants(): ?User
    {
        return $this->participants;
    }

    public function setParticipants(?User $participants): self
    {
        $this->participants = $participants;

        return $this;
    }
}
