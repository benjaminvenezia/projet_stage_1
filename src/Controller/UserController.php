<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\ChangepasswordType;
use App\Services\ThemesService;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    protected $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    /**
     * @Route("/user/gestion/informations", name="user_show")
     * @IsGranted("ROLE_USER")
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
     */
    public function deleteaccount(UserRepository $userRepository, ThemesService $themesService, EntityManagerInterface $em): Response
    {
       $user = $this->getUser();

       $em->remove($user);

       $em->flush();

       $this->addFlash('info', "Vous avez supprimé votre compte avec succès.");
       return $this->redirectToRoute('security_logout');
       
    }

    /**
     * @Route("/user/gestion/changepassword", name="user_changepassword")
     * @IsGranted("ROLE_USER")
     */
    public function changepassword(Request $request, ThemesService $themesService): Response
    {   
       
        $user = $this->getUser();
        
        $form = $this->createForm(ChangepasswordType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $oldPassword = $request->request->get('changepassword')['oldPassword'];
            $newPassword = $form->get('newPassword')->getData();
            
            $checkOldPassword = $this->hasher->isPasswordValid($this->getUser(), $oldPassword);

            if($checkOldPassword) {
                
                $em = $this->getDoctrine()->getManager();

                $newPasswordHashed = $this->hasher->hashPassword($user, $newPassword);

                $user->setPassword($newPasswordHashed);

                $em->flush();

                $this->addFlash('info', "Mot de passe modifié.");
                return $this->redirectToRoute('security_logout');
                
            }


        }

        return $this->render('user/changepassword.html.twig', [
            'formView' => $form->createView(),
            'themes' => $themesService->getThemes()
        ]);
    }
}
