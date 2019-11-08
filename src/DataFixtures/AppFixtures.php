<?php

namespace App\DataFixtures;

use App\Entity\Department;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $devDepartment = new Department(); 
        $devDepartment->setName('Dev'); 
        $devDepartment->setMail('ponche62880@gmail.com'); 
        $manager->persist($devDepartment); 

        $rhDepartment = new Department(); 
        $rhDepartment->setName('RH'); 
        $rhDepartment->setMail('ponche62880@gmail.com'); 
        $manager->persist($rhDepartment); 

        $directionDepartment = new Department(); 
        $directionDepartment->setName('Direction'); 
        $directionDepartment->setMail('ponche62880@gmail.com'); 
        $manager->persist($directionDepartment); 

        $comDepartment = new Department(); 
        $comDepartment->setName('Communication'); 
        $comDepartment->setMail('ponche62880@gmail.com'); 
        $manager->persist($comDepartment); 



        $manager->flush();
    }
}
