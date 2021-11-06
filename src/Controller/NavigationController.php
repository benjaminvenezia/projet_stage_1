<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\ThemeRepository;
use App\Services\ThemesService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NavigationController extends AbstractController
{
    
    protected EntityManagerInterface $em;
    protected ArticleRepository $articleRepository;
    protected ThemeRepository $themeRepository;
    protected ThemesService $themesService;

    public function __construct(EntityManagerInterface $em, ArticleRepository $articleRepository, ThemeRepository $themeRepository, ThemesService $themesService)
    {
        $this->em = $em;
        $this->articleRepository = $articleRepository;
        $this->themeRepository = $themeRepository;
        $this->themesService = $themesService;
    }

    /**
     * 
     * @Route("/", name="navigation_homepage")
     */
    public function homepage(ThemesService $themesService): Response
    {

        return $this->render('pages/homepage.html.twig', [
            
        ]);
    }

    /**
     * @Route("/{theme}", name="navigation_theme")
     */
    public function show($theme): Response
    {
        $themeId = $this->themeRepository->findOneBy(['name' => $theme]);
        $articles = $this->articleRepository->findBy(['theme' => $themeId]);
        // $themes = $this->themeRepository->findAll();

        // $themesNames = [];

        // foreach ($themes as $t) {
        //     $themesNames[] = $t->getName();
        // }
        $themesNames = $this->themesService->getThemes();


        return $this->render('pages/theme.html.twig', [
            'articles' => $articles,
            'themeName' => $theme,
            'themes' =>  $themesNames
        ]);
    }
}
