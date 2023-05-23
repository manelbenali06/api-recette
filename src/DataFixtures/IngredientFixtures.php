<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;

class IngredientFixtures extends AbstractFixtures
{
    /* classe IngredientFixtures génère 120 ingrédients avec des valeurs aléatoires pour les propriétés de chaque ingrédient, et les enregistre en base de données.*/
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 50; ++$i) {
            $ingredient = new Ingredient();
            /** @var string $name */
            /*  génère un tableau de 4 mots aléatoires et renvoie une chaîne de caractères qui les concatène. Chaque mot est généré de manière aléatoire en utilisant des mots courants de la langue par défaut de Faker. */
            $name = $this->faker->words(4, true);
            $ingredient
                ->setName($name)
                ->setDescription($this->faker->realText(125))/*générer une description aléatoire pour chaque instance d'ingrédient. */
                ->setDairyFree($this->faker->boolean())
                ->setGlutenFree($this->faker->boolean())
                ->setVegan($this->faker->boolean())
                ->setVegetarian($this->faker->boolean())
            ;
            $manager->persist($ingredient);
        }

        $manager->flush();
    }
}