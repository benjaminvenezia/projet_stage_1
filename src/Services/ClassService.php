<?php


namespace App\Services;

use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;

class ClassService {

    protected $em;

  
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

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
}
    
