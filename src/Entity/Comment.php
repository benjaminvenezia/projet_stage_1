<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use App\Repository\CommentRepository;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $User;

    /**
     * @ORM\Column(type="text", length=65535)
     * @Assert\NotBlank(message="Le commentaire ne peut être nul. ")
     * @Assert\Length(min=10, minMessage="Le commentaire doit comporter au minimum 10 caractères.")
     * @Assert\Length(max=3000, maxMessage="Le commentaire doit comporter au maximum 3000 caractères.")
     * 
     */
    private $commentary;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=Theme::class, inversedBy="comments")
     */
    private $theme;

    public function __construct()
    {
        $this->created_at = new \DateTime('now');
        
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCommentary(): ?string
    {
        return $this->commentary;
    }

    public function setCommentary(string $commentary): self
    {
        $this->commentary = $commentary;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    public function setTheme(?Theme $theme): self
    {
        $this->theme = $theme;

        return $this;
    }
}
