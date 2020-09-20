<?php

namespace App\DataFixtures;

use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PropertyFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for($i=0; $i<100; $i++){
            $property = new Property();
            $property->setTitle('Résidence '.$faker->company())
                    ->setDescription($faker->sentences(3, true))
                    ->setSurface($faker->numberBetween(20, 350))
                    ->setRooms($faker->numberBetween(2, 10))
                    ->setBedrooms($faker->numberBetween(1, 9))
                    ->setFloor($faker->numberBetween(0, 5))
                    ->setPrice($faker->numberBetween(100000, 1000000))
                    ->setHeat($faker->numberBetween(0, count(property::HEAT) -1))
                    ->setCity($faker->city)
                    ->setAddress($faker->streetAddress)
                    ->setPostalCode(str_replace(' ', '', $faker->postcode))
                    ->setIsSold(false);
            $manager->persist($property);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
