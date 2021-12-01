<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Services\ClassService;
use App\Services\ThemesService;
use App\Repository\ArticleRepository;
use App\Repository\ThemeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;

class ArticleController extends AbstractController
{

    protected $em;
    protected $articleRepository;
    protected $themesService;
    protected $upload;
  
    public function __construct(EntityManagerInterface $em, ArticleRepository $articleRepository)
    {
        $this->em = $em;
        $this->articleRepository = $articleRepository;
    }

    /**
     * @Route("admin/article/create", name="article_create")
     */
    public function create(Request $request, ClassService $classService): Response
    {
       
        $article = new Article;
        
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) { 

            $classService->uploadImage($form, $this->getParameter('kernel.project_dir') . '/assets/img');

            //Incrémentation du step, permet de calculter l'avancée de lecture.
            $idtheme = $form->getData('description')->getTheme()->getId();

            $step = $this->articleRepository->count(['theme' => $idtheme]) + 1;
            
            $article->setStep($step);
    
            $this->em->persist($article);

            $this->em->flush(); 

            $this->addFlash('success', "L'article a été crée avec succès.");

            return $this->redirectToRoute('navigation_theme', ['theme' => $article->getTheme()->getName()]);
        }

        return $this->render('article/create.html.twig', [
            'formView' => $form->createView(),
        ]);
    }


    /**
     * @Route("admin/article/{id}/edit", name="article_edit")
     */
    public function edit($id, Request $request, ThemesService $themesService, ClassService $classService): Response
    {
        $article = $this->articleRepository->find($id);

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $classService->uploadImage($form, $this->getParameter('kernel.project_dir') . '/assets/img');

            $this->em->flush();

            $this->addFlash('info', "L'article a été modifié avec succès.");
            return $this->redirectToRoute('administration_administrateArticles');
        }

        $formView = $form->createView();

        return $this->render('article/edit.html.twig', [
            'formView' => $formView,
        ]);
    }

    /**
     * @Route("admin/article/{id}/delete", name="article_delete")
     */
    public function delete($id, Request $request): Response
    {
        $article = $this->articleRepository->find($id);

        if(!$article) {
            throw new NotFoundHttpException("L'article que vous souhaitez supprimer n'existe pas");
        } 

        $this->em->remove($article);

        $this->em->flush();

        return $this->redirectToRoute('administration_administrateArticles');
    }

     /**
     * Show an article by is theme and is step, very intersting for navigation because articles have step. 
     * @Route("article/{theme}/{step}/show", name="article_show")
     */
    public function show($theme, $step, ThemeRepository $themeRepository, ArticleRepository $articleRepository): Response
    {
        $theme = $themeRepository->findOneBy(['name' => $theme]);
        $themeid = $theme->getId();

        $article = $articleRepository->findOneBy(['theme' => $themeid, 'step' => $step]);
        
        if(!$article) {

            throw new NotFoundHttpException("erreur, article introuvable. ");
        }

        $totalArticlesByTheme = $articleRepository->count(['theme' => $themeid]);

        $stepOfTheArticle = $article->getStep();
        $percentage = ( $stepOfTheArticle / $totalArticlesByTheme  ) * 100;

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'theme' => $theme->getName(),
            'ReadingPercentage' => $percentage,
            'nextArticleStep' => $article->getStep() + 1
        ]);
    }

    /**
     *  Show an article with is id only. Useful for bookmark functionality
     * @Route("article/{id}/show", name="article_showById")
     */
    public function showById($id, Request $request, ThemesService $themesService, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->findOneBy(['id' => $id]);

        if(!$article) {
            throw new NotFoundHttpException("erreur, article introuvable. ");
        }
        $idtheme = $article->getTheme()->getId();

        $totalArticlesByTheme = $articleRepository->count(['theme' => $idtheme]);

        $numberOfTheArticle = $article->getStep();
        $percentage = ( $numberOfTheArticle / $totalArticlesByTheme  ) * 100;

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'theme' => $article->getTheme()->getName(),
            'ReadingPercentage' => $percentage,
            'nextArticleStep' => $article->getStep() + 1
        ]);
    }
}
