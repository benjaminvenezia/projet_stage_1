<?php

namespace App\DataFixtures;

use App\Entity\Theme;
use App\Entity\Article;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {   
        $theme1 = new Theme;
        $theme1->setName('spring');
        $manager->persist($theme1);

        $theme2 = new Theme;
        $theme2->setName('summer');
        $manager->persist($theme2);

        $theme3 = new Theme;
        $theme3->setName('autumn');
        $manager->persist($theme3);

        $theme4 = new Theme;
        $theme4->setName('winter');
        $manager->persist($theme4);

        $theme5 = new Theme;
        $theme5->setName('spring2');
        $manager->persist($theme5);

        /**
         * @var array<Theme>
         */
        $themes = [$theme1, $theme2, $theme3, $theme4, $theme5];



        for ($a=1; $a <= 6; $a++) { 
            $article = new Article();

            $article->setAuthor('Admin')
               ->setTheme($themes[random_int(1, 6)])
               ->setTitle('Titre ' . $a)
               ->setDescription("Ce stage a donc été une opportunité pour moi de percevoir comment une entreprise dans un secteur [décrire ici les caractéristiques du secteur : concurrence, évolution, historique, acteurs… et quelle stratégie l’entreprise a choisie dans ce secteur. Ainsi que l’apport du département et du poste occupé dans cette stratégie…]
               L’élaboration de ce rapport a pour principale source les différents enseignements tirés de la pratique journalière des tâches auxquelles j’étais affecté. Enfin, les nombreux entretiens que j’ai pu avoir avec les employés des différents services de la société m’ont permis de donner une cohérence à ce rapport.")
               ->setStep($a)
               ->setDate(new DateTime('now'));
            $manager->persist($article);
        }

 
        $manager->flush();
    }
}
