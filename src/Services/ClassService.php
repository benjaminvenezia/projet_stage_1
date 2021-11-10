<?php


namespace App\Services;

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
            $request->query->getInt('page', $pages),
            1
        );
        
        return $elements;
    }
}
    
