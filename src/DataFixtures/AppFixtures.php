<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;

    /**
     * AppFixtures constructor.
     */
    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word());
            $ingredient->setPrice(mt_rand(1,100));

            $manager->persist($ingredient);
        }
        $manager->flush();
    }
}
