<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Services\ThemesService;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user/gestion/informations", name="user_show")
     * @IsGranted("ROLE_USER")
     * 
     * 
     */
    public function show(CommentRepository $commentRepository, ThemesService $themesService): Response
    {
        
        $username = $this->getUser()->getUsername();
        $mail = $this->getUser()->getUserIdentifier();
        $roles = $this->getUser()->getRoles();
        $isVerified = $this->getUser()->getIsVerified();
        $created_at = $this->getUser()->getCreatedAt();

        
        // $comments = $commentRepository->findBy(['user' => $this->getUser()]);

        return $this->render('user/show.html.twig', [
            'username' => $username,
            'mail' => $mail,
            'roles' => $roles,
            'isVerified' => $isVerified, 
            'createdAt' => $created_at,
            'themes' => $themesService->getThemes()
        ]);
    }

    /**
     * @Route("/user/gestion/deleteaccount", name="user_deleteaccount")
     * @IsGranted("ROLE_USER")
     * 
     */
    public function deleteaccount(UserRepository $userRepository, ThemesService $themesService, EntityManagerInterface $em): Response
    {
       $user = $this->getUser();

       $em->remove($user);

       $this->addFlash('info', "Vous avez supprimé votre compte avec succès.");
       return $this->redirectToRoute('security_logout');
       
    }
}
