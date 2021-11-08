<?php

namespace App\Controller;

use App\Form\LoginType;
use App\Services\ThemesService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $utils, ThemesService $themesService): Response
    {
        // if ($this->getUser()) {
        //     // return $this->redirectToRoute('target_path');
        // }
        // $form = $this->createForm(LoginType::class);

        // get the login error if there is one
        $error = $utils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $utils->getLastUsername();
        
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error,
            // 'formView' => $form->createView(),
            'themes' => $themesService->getThemes()
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(): Response
    {
         throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
