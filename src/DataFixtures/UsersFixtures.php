<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\StdPerInfor;
use Faker\Factory;

class UsersFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {      
    	$faker = Factory::craete('fr_FR');

    	for($i=0; $i<20; $i++){
    		$stdinfo = new StdPerInfo();
            $stdinfo->setName($faker->name())
                    ->setPicture($faker->imageUrl());
    		$manager->persist($stdinfo);
    	}
       
       

        $manager->flush();
    }
}
