<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdministrationController extends AbstractController
{

    protected EntityManagerInterface $em;
    protected ArticleRepository $articleRepository;
    protected ThemeRepository $themeRepository;

    public function __construct(EntityManagerInterface $em, ArticleRepository $articleRepository, ThemeRepository $themeRepository)
    {
        $this->em = $em;
        $this->articleRepository = $articleRepository;
        $this->themeRepository = $themeRepository;
    }


    /**
     * @Route("admin/administration", name="administration_administrate")
     */
    public function administrate(): Response
    {
        $themes = $this->themeRepository->findAll();
        $articles = $this->articleRepository->findAll();
        
        /**
         * @var array<int>
         */
        $themesId = [];

        /**
         * @var array<string>
         */
        $themesNames = [];

        foreach ($themes as $t) {
            $themesId[] = $t->getId();
            $themesNames[] = $t->getName();
        }
        /**
         * @var array<Article>
         */
        $articles_spring = $this->articleRepository->findBy(['theme' => $themesId[0]]);
        
        /**
         * @var array<Article>
         */
        $articles_summer = $this->articleRepository->findBy(['theme' => $themesId[1]]);
        
        /**
         * @var array<Article>
         */
        $articles_autumn = $this->articleRepository->findBy(['theme' => $themesId[2]]);
       
        /**
         * @var array<Article>
         */
        $articles_winter = $this->articleRepository->findBy(['theme' => $themesId[3]]);
        
        /**
         * @var array<Article>
         */
        $articles_spring2 = $this->articleRepository->findBy(['theme' => $themesId[4]]);


        return $this->render('administration/index.html.twig', [
            'articles_spring' => $articles_spring,
            'articles_summer' => $articles_summer,
            'articles_autumn' => $articles_autumn,
            'articles_winter' => $articles_winter,
            'articles_spring2' => $articles_spring2,
            'articles' => $articles,

            'themesNames' => $themesNames,
        ]);
    }
}
