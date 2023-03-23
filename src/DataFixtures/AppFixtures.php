<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Publisher;
use App\Factory\AuthorFactory;
use App\Factory\BookFactory;
use App\Factory\PublisherFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        AuthorFactory::createMany(50);

        PublisherFactory::createOne(['name' => 'Grasset']);
        PublisherFactory::createOne(['name' => 'Hachette']);
        PublisherFactory::createOne(['name' => 'PUF']);
        PublisherFactory::createOne(['name' => 'Gallimard']);
        PublisherFactory::createOne(['name' => 'Harper & Collins']);
        PublisherFactory::createOne(['name' => 'Bean Books']);

        BookFactory::createMany(300, function (){
            return [
                'publisher' => PublisherFactory::random(),
                'author' => AuthorFactory::random()
            ];
        });



        // Création des auteurs
        $author = new Author();
        $author->setFirstName('Pablo')
            ->setLastName('Neruda');
        $manager->persist($author);
        $this->addReference('author_1', $author);

        $author = new Author();
        $author->setFirstName('Jorge Luis')
            ->setLastName('Borges');
        $manager->persist($author);
        $this->addReference('author_2', $author);

        $author = new Author();
        $author->setFirstName('Emily')
            ->setLastName('Dickinson');
        $manager->persist($author);
        $this->addReference('author_3', $author);

        // création des éditeurs
        $publisher = new Publisher();
        $publisher->setName('PUF')->setCity('Paris');
        $manager->persist($publisher);
        $this->addReference('publisher_1', $publisher);

        $publisher = new Publisher();
        $publisher->setName('Harper & Collins')->setCity('New York');
        $manager->persist($publisher);
        $this->addReference('publisher_2', $publisher);

        $publisher = new Publisher();
        $publisher->setName('Grasset')->setCity('Paris');
        $manager->persist($publisher);
        $this->addReference('publisher_3', $publisher);


        $manager->flush();
    }
}
