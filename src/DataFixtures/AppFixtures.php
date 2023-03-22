<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création des auteurs
        $author = new Author();
        $author->setFirstName('Pablo')
            ->setLastName('Neruda');
        $manager->persist($author);

        $author = new Author();
        $author->setFirstName('Jorge Luis')
            ->setLastName('Borges');
        $manager->persist($author);

        $author = new Author();
        $author->setFirstName('Emily')
            ->setLastName('Dickinson');
        $manager->persist($author);

        $manager->flush();
    }
}
