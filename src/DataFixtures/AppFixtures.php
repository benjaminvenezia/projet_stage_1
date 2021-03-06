<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use App\Entity\Theme;
use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    protected $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {   
        $admin = new User;

        $hash = $this->hasher->hashPassword($admin, 'password');

        $admin->setEmail("admin@gmail.com")
        ->setPassword($hash)
        ->setFullName("Admin")
        ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);
        
        $users = [];

        for($u = 0; $u < 20; $u++) {
            $user = new User();

            $hash = $this->hasher->hashPassword($user,"password");

            $user->setEmail("user$u@gmail.com")
            ->setFullName('pseudo')
            ->setPassword($hash)
            ->setRoles(["ROLE_USER"]);

            $users[] = $user;

            $manager->persist($user);
            
        }




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

        for ($a=1; $a <= 20; $a++) { 
            $article = new Article();

            $article->setAuthor('Admin')
               ->setTheme($themes[random_int(0, 4)])
               ->setTitle('Titre ' . $a)
               ->setDescription("Ce stage a donc ??t?? une opportunit?? pour moi de percevoir comment une entreprise dans un secteur [d??crire ici les caract??ristiques du secteur : concurrence, ??volution, historique, acteurs??? et quelle strat??gie l???entreprise a choisie dans ce secteur. Ainsi que l???apport du d??partement et du poste occup?? dans cette strat??gie???]
               L?????laboration de ce rapport a pour principale source les diff??rents enseignements tir??s de la pratique journali??re des t??ches auxquelles j?????tais affect??. Enfin, les nombreux entretiens que j???ai pu avoir avec les employ??s des diff??rents services de la soci??t?? m???ont permis de donner une coh??rence ?? ce rapport.")
               ->setStep($a)
               ->setDate(new DateTime('now'));
            $manager->persist($article);
        }

 
        $manager->flush();
    }
}
