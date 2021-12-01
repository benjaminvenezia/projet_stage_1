<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\User;
use App\Entity\Comment;
use App\Services\ClassService;
use App\Form\ChangepasswordType;
use App\Form\RoledescriptionType;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function show(Request $request, CommentRepository $commentRepository): Response
    {
        $username = $this->getUser()->getUsername();
        $mail = $this->getUser()->getUserIdentifier();
        $roles = $this->getUser()->getRoles();
        $isVerified = $this->getUser()->getIsVerified();
        $created_at = $this->getUser()->getCreatedAt();
        $roledescription = $this->getUser()->getRoledescription();

        $form = $this->createForm(RoledescriptionType::class, $this->getUser());

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->flush();
            $this->addFlash('info', "opération réussie, ");
            return $this->redirectToRoute('user_show');
            
        }

        
        // $comments = $commentRepository->findBy(['user' => $this->getUser()]);

        return $this->render('user/show.html.twig', [
            'formView' => $form->createView(),
            'username' => $username,
            'mail' => $mail,
            'roles' => $roles,
            'isVerified' => $isVerified, 
            'createdAt' => $created_at,
        ]);
    }

    /**
     * @Route("/user/gestion/deleteaccount", name="user_deleteaccount")
     * @IsGranted("ROLE_USER")
     */
    public function deleteaccount(UserRepository $userRepository,  EntityManagerInterface $em): Response
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
    public function changepassword(Request $request): Response
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
        ]);
    }

    /**
     * Allow the connected user to save an article for later. (bookmark)
     * @Route("/user/{article_theme}/{article_id}/{article_step}/bookmark", name="user_bookmark")
     * @IsGranted("ROLE_USER")
     */
    public function bookmark($article_id, $article_theme, $article_step): Response
    {
        $em = $this->getDoctrine()->getManager();
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $user->setBookmark($article_id);

        $em->persist($user);
        $em->flush();

        $this->addFlash('success', "Vous avez placé un marque page sur cet article. Vous pourrez le retrouver dans la barre de navigation. ");

        return $this->redirectToRoute('article_show', ['theme' => $article_theme, 'step' => $article_step, 'article_id' => $article_id]);
        
    }

    /**
     * @Route("/user/downloadpdf", name="user_downloadpdf")
     */
    public function downloadpdf(ArticleRepository $articleRepository, ClassService $classService)
    {
        require '../vendor/autoload.php';

        $options = new Options();
        $options->set('defaultFont', 'Courier');

        /**
         * @var array<Article>
         */
        $articles = $articleRepository->findAll();
        
        $html = $classService->renderHtml($articles);


        // instantiate and use the dompdf class
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml(

            $html
        
        );

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();
        
        $fichier = 'rapport-de-stage-benjamin-crea-2021.pdf';
        // Output the generated PDF to Browser
        $dompdf->stream($fichier);
    

        return new Response('', 200, [
            'Content-Type' => 'application/pdf',
          ]);
    }


    /**
     * @Route("/user/downloadpdfv2", name="user_downloadpdfversioncorrection")
     */
    public function downloadpdfversioncorrection(ArticleRepository $articleRepository, ClassService $classService)
    {
        require '../vendor/autoload.php';

        $options = new Options();
        $options->set('defaultFont', 'Courier');

        /**
         * @var array<Article>
         */
        $articles = $articleRepository->findAll();
        
        $html = $classService->renderHtmlversioncorrection($articles);

        // instantiate and use the dompdf class
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();
        
        $fichier = 'rapport-de-stage-benjamin-crea-2021-version-correction.pdf';
        // Output the generated PDF to Browser
        $dompdf->stream($fichier);
    

        return new Response('', 200, [
            'Content-Type' => 'application/pdf',
          ]);
    }

}
