<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\ThemeRepository;
use App\Services\ClassService;
use App\Services\ThemesService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * @Route("/{theme}", name="navigation_theme", priority=-1)
     */
    public function show(Request $request, $theme, ClassService $classService, CommentRepository $commentRepository, ThemeRepository $themeRepository): Response
    {
        $themeId = $this->themeRepository->findOneBy(['name' => $theme]);
        $theme_object = $this->themeRepository->findOneBy(['id' => $themeId]);
        $articles = $this->articleRepository->findBy(['theme' => $themeId]);

        $articlesPaginated = $classService->paginate(1, $articles, $request);


        //ajout form commentaire
        $comment = new Comment;
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $comment
            ->setTheme($theme_object)
            ->setUser($this->getUser())
            ;

            $this->em->persist($comment);
            $this->em->flush();

            $this->addFlash('success', "Merci pour le commentaire.");
            return $this->redirectToRoute('navigation_theme', ['theme' =>$theme]);
        }

        $themeObject = $themeRepository->findOneBy(['name' => strtolower($theme)]);

        if(!$themeObject) {
            throw new NotFoundHttpException("Une erreur a eu lieu");
        }

        $comments = $commentRepository->findBy(['theme' => $themeObject->getId()]);
        $comments = $classService->paginate(30, $comments, $request);

        return $this->render('pages/theme.html.twig', [
            'articles' => $articles,
            'articlesPaginated' => $articlesPaginated,
            'themeName' => $theme,
            'themes' =>  $this->themesService->getThemes(),
            'formComment' => $form->createView(),
            'comments' => $comments,
        ]);
    }
}
