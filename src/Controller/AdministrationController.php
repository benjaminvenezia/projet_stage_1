<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Services\ClassService;
use App\Repository\UserRepository;
use App\Repository\ThemeRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdministrationController extends AbstractController
{

    protected EntityManagerInterface $em;
    protected ArticleRepository $articleRepository;
    protected ThemeRepository $themeRepository;
    const ADMIN_ROLE = "ROLE_ADMIN";
    const USER_ROLE = "ROLE_USER";
    const SUPER_ADMIN_ROLE = "ROLE_SUPER_ADMIN";



    public function __construct(EntityManagerInterface $em, ArticleRepository $articleRepository, ThemeRepository $themeRepository)
    {
        $this->em = $em;
        $this->articleRepository = $articleRepository;
        $this->themeRepository = $themeRepository;
    }

      /**
     * @Route("/admin/{id}/ban", name="administration_banuser")
     * @IsGranted("ROLE_ADMIN")
     */
    public function banuser($id, UserRepository $userRepository,  EntityManagerInterface $em): Response
    {
       $user = $userRepository->find($id);

       $roleUser = $user->getRole();

       if($roleUser == 'ROLE_ADMIN') {
            $this->addFlash('info', "Vous ne pouvez pas ôter les droits d'un administrateur");
            return $this->redirectToRoute('administration_administrateUsers');
       } else {
            if(!$user->getBanned()) {
                $user->setBanned(1);
            } else {
                $user->setBanned(0);
            }
       }

       $em->flush();

       $this->addFlash('info', "Vous avez banni cet utilisateur");
       return $this->redirectToRoute('administration_administrateUsers');
    }


    /**
     * @Route("admin/administration/articles", name="administration_administrateArticles")
     */
    public function administrateArticles(): Response
    {
        $themes = $this->themeRepository->findAll();
        $articles = $this->articleRepository->findAll();
        
        /**
         * @var array<int>
         */
        $themesId = [];

        /**
         * @var array<string>
         */
        $themesNames = [];

        foreach ($themes as $t) {
            $themesId[] = $t->getId();
            $themesNames[] = $t->getName();
        }
        /**
         * @var array<Article>
         */
        $articles_spring = $this->articleRepository->findBy(['theme' => $themesId[0]]);
        
        /**
         * @var array<Article>
         */
        $articles_summer = $this->articleRepository->findBy(['theme' => $themesId[1]]);
        
        /**
         * @var array<Article>
         */
        $articles_autumn = $this->articleRepository->findBy(['theme' => $themesId[2]]);
       
        /**
         * @var array<Article>
         */
        $articles_winter = $this->articleRepository->findBy(['theme' => $themesId[3]]);
        
        /**
         * @var array<Article>
         */
        $articles_spring2 = $this->articleRepository->findBy(['theme' => $themesId[4]]);


        return $this->render('administration/articles.html.twig', [
            'articles_spring' => $articles_spring,
            'articles_summer' => $articles_summer,
            'articles_autumn' => $articles_autumn,
            'articles_winter' => $articles_winter,
            'articles_spring2' => $articles_spring2,
            'articles' => $articles,
            'themesNames' => $themesNames,
        ]);
    }

    /**
     * @Route("admin/administration/users", name="administration_administrateUsers")
     */
    public function administrateUsers(Request $request, ClassService $classService, UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        $users = $classService->paginate(7, $users, $request);

        return $this->render('administration/users.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("admin/administration/users/changestatut/{id}", name="administration_changestatut")
     */
    public function changestatut($id, UserRepository $userRepository)
    {
            $user = $userRepository->find($id);
            
            $roles = $user->getRoles();

            //empêcher le super admin de changer de rôle. à faire
            // if($roles[0] === $this::SUPER_ADMIN_ROLE) {
            //     $this->addFlash('warning', "Impossible de modifier le rôle de super admin.");
            //     return $this->redirectToRoute('administration_administrateUsers', ['page' => 1]);
            // }

            if ($roles[0] === $this::ADMIN_ROLE) {
                $roles[0] = $this::USER_ROLE;
            } else {
                $roles[0] = $this::ADMIN_ROLE;
            }
           
            $user->setRoles($roles);

            $this->em->flush();
            $this->addFlash('warning', "Vous avez bien changé le statut de " . $user->getEmail() . " à " . $roles[0]);
         
            return $this->redirectToRoute('administration_administrateUsers', ['page' => 1]);
        
    }

     /**
     * @Route("admin/administration/users/search", name="administration_search")
     */
    public function search(UserRepository $userRepository)
    {

            $email = $_POST['search'];

            try {
                $user = $userRepository->findOneBy(['email' => $email]);
            } catch(\Exception $e) {
                return $this->render('pages/homepage.html.twig');
                $e->getMessage();
            }

            return $this->render('administration/user.html.twig', [
                'user' => $user
            ]);
        
    }
 }
