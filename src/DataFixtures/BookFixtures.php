<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        /**
         * @var Author $author2
         */
        $author2 = $this->getReference('author_2');
        $book = new Book();
        $book->setTitle('Poésie')
            ->setPublishedAt(new \DateTime('now -16 years'))
            ->setAuthor($author2)
            ->setPublisher($this->getReference('publisher_1'));
        $manager->persist($book);

        $book = new Book();
        $book->setTitle('UML sans se fatiguer')
            ->setPublishedAt(new \DateTime('now -12 years'))
            ->setAuthor($author2)
            ->setPublisher($this->getReference('publisher_1'));
        $manager->persist($book);

        $book = new Book();
        $book->setTitle('49,3 raisons de partir à la retraite')
            ->setPublishedAt(new \DateTime('now 90 months'))
            ->setAuthor($this->getReference('author_1'))
            ->setPublisher($this->getReference('publisher_3'));
        $manager->persist($book);


        $manager->flush();
    }
}
