<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\VisitCounter;
use App\Form\CommentType;
use App\Services\ClassService;
use App\Form\SearchArticlesType;
use App\Repository\ThemeRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\VisitCounterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Ip;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NavigationController extends AbstractController
{
    
    protected EntityManagerInterface $em;
    protected ArticleRepository $articleRepository;
    protected ThemeRepository $themeRepository;
    

    public function __construct(EntityManagerInterface $em, ArticleRepository $articleRepository, ThemeRepository $themeRepository)
    {
        $this->em = $em;
        $this->articleRepository = $articleRepository;
        $this->themeRepository = $themeRepository;
    }

    

    /**
     * @Route("/", name="navigation_homepage")
     */
    public function homepage(VisitCounterRepository $visitCounterRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $visit = new VisitCounter;
        $user_ip = $_SERVER['REMOTE_ADDR'];

        $total = $visitCounterRepository->count([]);

        if($visitCounterRepository->findOneBy(['ip_adress' => $user_ip])){
            //on n'incrémente pas le nombre de visite.
        } else {
            $visit->setSum($total++);
            $visit->setIpAdress($user_ip);
            $em->persist($visit);
            $em->flush();
        }
        
        return $this->render('pages/homepage.html.twig', [
            'visitors' => $visitCounterRepository->count([])
        ]);
    }

    /**
     * @Route("/download", name="navigation_download")
     */
    public function download(): Response
    {
        return $this->render('pages/download.html.twig');
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

        //ajout search articles
        $searchForm = $this->createForm(SearchArticlesType::class);

        $search = $searchForm->handleRequest($request);

        if($searchForm->isSubmitted() && $searchForm->isValid()) {
            //on recherche les articles correspondants aux mots-clés
            $articlesresult = $this->articleRepository->search($search->get('mots')->getData());
       
            return $this->render('pages/searchResult.html.twig', [
                'search' => $search->get('mots')->getData(),
                'articles' => $articlesresult,
                'themeName' => $theme,
            ]);
        }

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
            return $this->redirectToRoute('navigation_theme', ['theme' => $theme]);
        }

        $themeObject = $themeRepository->findOneBy(['name' => strtolower($theme)]);

        if(!$themeObject) {
            throw new NotFoundHttpException("Une erreur a eu lieu");
        }

        $comments = $commentRepository->findBy(['theme' => $themeObject->getId()]);
        $comments = $classService->paginate(10, $comments, $request);
    
        return $this->render('pages/theme.html.twig', [
            'articles' => $articles,
            'articlesPaginated' => $articlesPaginated,
            'themeName' => $theme,
            'formComment' => $form->createView(),
            'comments' => $comments,
            'searchForm' => $searchForm->createView(),
        ]);
    }
}
