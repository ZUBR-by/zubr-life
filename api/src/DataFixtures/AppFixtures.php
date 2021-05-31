<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager) : void
    {
        $product = new User(1);
        $manager->persist($product);
        $product1 = new User(2, [], new DateTime());
        $manager->persist($product1);
        $manager->flush();
    }
}
