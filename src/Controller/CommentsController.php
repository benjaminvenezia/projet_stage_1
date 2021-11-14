<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\ThemeRepository;
use App\Services\ThemesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CommentsController extends AbstractController
{
    /**
     * @Route("/comments/show/{theme}", name="comments_show")
     */
    // public function show($theme, CommentRepository $commentRepository, ThemeRepository $themeRepository, ThemesService $themesService): Response
    // {
       

    //     $themeObject = $themeRepository->findOneBy(['name' => strtolower($theme)]);

    //     if(!$themeObject) {
    //         throw new NotFoundHttpException("Une erreur a eu lieu");
    //     }

    //     $comments = $commentRepository->findBy(['theme' => $themeObject->getId()]);

    //     return $this->render('pages/theme.html.twig', [
    //         'comments' => $comments,
          
    //     ]);
      
    // }
}
