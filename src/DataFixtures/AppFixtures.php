<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

### i use the fixture to add data to the table esier
class AppFixtures extends Fixture
{
    private $passwordHasher ;
    /*
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->$passwordHasher = $passwordHasher ;
    }
    */
    
    
    # load fuction can't hold the UserPasswordHasherInterface parameter 
    # that why we used an constructer
    public function load(ObjectManager $manager): void
    {
        $user = new User(); 
        $plainPassword = "$2y$10$//.elqbXc17Szk9M4VGBxevRhRlFy06Duk6izzuCVTDXxuLlkHPkW" ;
        //$hashedPassword = $this->passwordHasher ->hashedPassword($user, $plainPassword);

        $user->setUsername('admim');
        $user->setPassword($plainPassword);
        $user->setRoles(['ROLE_ADMIN']); 
        $manager->persist($user ); 

        $manager->flush();
    }
}
