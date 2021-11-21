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
     * Supprime un commentaire depuis l'espace commentaire.
     * @Route("/{theme}/comments/{idComment}/delete", name="comments_delete")
     */
    public function delete($theme, $idComment, CommentRepository $commentRepository, ThemeRepository $themeRepository, ThemesService $themesService): Response
    {
        $em = $this->getDoctrine()->getManager();

        $commentToDelete = $commentRepository->find($idComment);

        if($this->getUser()->getId() !== $commentToDelete->getUser()->getId()) {
            throw new NotFoundHttpException("Ce commentaire n'est pas le votre.");
        }
        
        if(!$commentToDelete) {
            throw new NotFoundHttpException("commentaire introuvable.");
        }

        $em->remove($commentToDelete);
      
        $em->flush();
     
        $this->addFlash('success', 'L\'article a bien été supprimé.');
     
        return $this->redirectToRoute('navigation_theme', ['theme' => $theme]);

      
    }
}
