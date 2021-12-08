<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @ORM\Table(name="article", indexes={@ORM\Index(columns={"title", "description"}, flags={"fulltext"})})
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'article doit être pourvu d'un titre.'")
     * @Assert\Length(min=3, minMessage="Le titre doit comporté au minimum 3 caractères-")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="La description est obligatoire.")
     * @Assert\Length(min=20, minMessage="La description doit au minimum faire 20 caractères.")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $step;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $author;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Theme::class, inversedBy="theme_id")
     * @Assert\NotBlank(message="Il faut choisir un thème valide.")
     */
    private $theme;

    /**
     * @ORM\OneToOne(targetEntity="Image", cascade={"persist", "remove"})
     * @Assert\NotBlank(message="Image requise.")
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="article")
     */
    private $comments;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->date = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getStep(): ?int
    {
        return $this->step;
    }

    public function setStep(int $step): self
    {
        $this->step = $step;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage($image): void
    {
        $this->image = $image;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    // public function addComment(Comment $comment): self
    // {
    //     if (!$this->comments->contains($comment)) {
    //         $this->comments[] = $comment;
    //         $comment->setArticle($this);
    //     }

    //     return $this;
    // }

    // public function removeComment(Comment $comment): self
    // {
    //     if ($this->comments->removeElement($comment)) {
    //         // set the owning side to null (unless already changed)
    //         if ($comment->getArticle() === $this) {
    //             $comment->setArticle(null);
    //         }
    //     }

    //     return $this;
    // }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }
}
