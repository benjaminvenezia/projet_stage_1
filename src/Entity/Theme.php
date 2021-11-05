<?php

namespace App\Entity;

use App\Repository\ThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ThemeRepository::class)
 */
class Theme
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="theme")
     */
    private $theme_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function __construct()
    {
        $this->theme_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Article[]
     */
    public function getThemeId(): Collection
    {
        return $this->theme_id;
    }

    public function addThemeId(Article $themeId): self
    {
        if (!$this->theme_id->contains($themeId)) {
            $this->theme_id[] = $themeId;
            $themeId->setTheme($this);
        }

        return $this;
    }

    public function removeThemeId(Article $themeId): self
    {
        if ($this->theme_id->removeElement($themeId)) {
            // set the owning side to null (unless already changed)
            if ($themeId->getTheme() === $this) {
                $themeId->setTheme(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
