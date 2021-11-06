<?php

namespace App\Services;

use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;

class ThemesService {

    protected $em;
    protected $themeRepository;
    protected $twig;
  
    public function __construct(EntityManagerInterface $em, ThemeRepository $themeRepository, Environment $twig)
    {
        $this->em = $em;
        $this->themeRepository = $themeRepository;
        $this->twig = $twig;
    }

    public function getThemes(): array
    {
      
        $themes = $this->themeRepository->findAll();

        $themesNames = [];

        foreach ($themes as $t) {
            $themesNames[] = $t->getName();
        }

        return $themesNames;
      
    }
}
    