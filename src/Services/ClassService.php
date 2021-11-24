<?php


namespace App\Services;

use Dompdf\Dompdf;
use Twig\Environment;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class ClassService {

    protected $em;
    protected $paginator;

  
    public function __construct(EntityManagerInterface $em , PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
        $this->em = $em;
    }

    /**
     * Utilisé pour persister des images dans la base de données
     */
    public function uploadImage($form, $path)
    {
            //récupère les valeurs soumises sous forme d'objet Image
            $infos = $form->getData();
            //récuperer un objet image image (name vide)
            $image = $infos->getImage(); 
            //récupère le file soumis
            /**
             * @var UploadedFile $file
             */
            $file = $image->getFile();
            //créer un nom unique
            $name = md5(uniqid()). '.' . $file->guessExtension();
    
            //déplace le fichgier
            $file->move($path, $name);

            //attribue le nom à l'image
            $image->setName($name);
    }

    public function paginate(int $pages, $elements, Request $request) {
        $elements = $this->paginator->paginate(
            $elements, /* query NOT result */
            $request->query->getInt('page', 1), //current page number
            $pages //image per page
        );
        
        return $elements;
    }

    public function renderHtml($articles) {

        $html = '';

        
        foreach($articles as $a) {
            $html .= '<h1>' . $a->getTitle()  . '(' . $a->getStep() . ')' . '</h1>';
            $html .= $a->getTheme()->getName();
            $html .= $a->getDescription();
        }

        return $html;
    }

    public function renderHtmlversioncorrection($articles) {

        $html = '';

        $html .= '<h1>Version pour correction avec zones d\'annotations</h1>';

        foreach($articles as $a) {

            $html .= '<h1>' . $a->getTitle()  . '(' . $a->getStep() . ')' . '</h1>';
            $html .= $a->getTheme()->getName();
            $html .= $a->getDescription();
            $html .= '<p>Notes :</p>';
            $html .= "<div style='border: 1px solid black; padding:200px;'></div>";
           
        }

        return $html;
    }

}
    
