<?php

namespace App\Controller;

use App\Entity\Theme;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use App\Services\MailerService;
use App\Services\ThemesService;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{

    protected $userPasswordHasher;
    protected $mailerService;
    protected $themesService;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface, MailerService $mailerService, ThemesService $themesService){
        $this->userPasswordHasher = $userPasswordHasherInterface;
        $this->mailerService = $mailerService;
        $this->themesService = $themesService;
    }

    /**
     * @Route("/inscription", name="registration_register")
     */
    public function register(Request $request) : Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $this->userPasswordHasher->hashPassword($user, $form->get("password")->getData())
            );

            $user->setToken($this->generateToken());

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->mailerService->sendEmail($user->getEmail(), $user->getToken());

            $this->addFlash('success', "Une demande a été envoyée à " . $user->getEmail());

            return $this->redirectToRoute('navigation_theme', ['theme' => 'spring']);
        }

        return $this->render('registration/register.html.twig', [
            'themes' => $this->themesService->getThemes(),
            'formView' => $form->createView()
        ]);
    }

    /**
     * @Route("/confirmer-mon-compte/{token}", name="confirm_account")
     */
    public function confirmAccount(string $token, UserRepository $userRepository) 
    {
        $user = $userRepository->findOneBy(["token" => $token]);

        if($user) {
            $user->setToken(null);
            $user->setIsVerified(true);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', "Le compte a bien été vérifié!");
            return $this->redirectToRoute('navigation_theme', ['theme' => 'spring']);
        } else {
            return $this->redirectToRoute('navigation_theme', ['theme' => 'spring']);

            $this->addFlash('error', "Ce compte n'existe pas");

        }

        return $this->json($token);

    }


    /**
     * @return string
     * @throws \Exception
     */
    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }




}