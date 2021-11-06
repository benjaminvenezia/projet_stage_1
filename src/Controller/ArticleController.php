<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Services\ThemesService;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class ArticleController extends AbstractController
{

    protected $em;
    protected $articleRepository;
    protected $themesService;
  
    public function __construct(EntityManagerInterface $em, ArticleRepository $articleRepository, ThemesService $themesService)
    {
        $this->em = $em;
        $this->articleRepository = $articleRepository;
        $this->themesService = $themesService;
    }

    /**
     * @Route("admin/article/create", name="article_create")
     */
    public function create(Request $request): Response
    {
        $article = new Article;
        
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {   
            $step = $this->articleRepository->count([]);
            $step++;

            $article->setStep($step);
            $article->setDate(new DateTime('now'));
            $this->em->persist($article);

            $this->em->flush(); 
            return $this->redirectToRoute('navigation_theme', ['theme' => $article->getTheme()->getName()]);
        }

        return $this->render('article/create.html.twig', [
            'formView' => $form->createView(),
            'themes' => $this->themesService->getThemes(),
        ]);
    }


    /**
     * @Route("admin/article/{id}/edit", name="article_edit")
     */
    public function edit($id, Request $request): Response
    {
        
        $article = $this->articleRepository->find($id);

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('administration_administrate');
        }

        $formView = $form->createView();

        return $this->render('article/create.html.twig', [
            'formView' => $formView
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
        return $this->redirectToRoute('administration_administrate');
    }
        

}
