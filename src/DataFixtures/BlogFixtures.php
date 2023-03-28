<?php

namespace App\DataFixtures;

use App\Factory\ArticleFactory;
use App\Factory\AuthorFactory;
use App\Factory\CommentFactory;
use App\Factory\TagFactory;
use App\Factory\ThemeFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BlogFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(15);
        UserFactory::createOne([
            'nickName' => 'user1'
        ]);
        UserFactory::createOne([
            'nickName' => 'joe'
        ]);
        UserFactory::createOne([
            'nickName' => 'beth'
        ]);



        ThemeFactory::createOne(['themeName'=> 'Politique']);
        ThemeFactory::createOne(['themeName'=> 'Economie']);
        ThemeFactory::createOne(['themeName'=> 'Société']);
        ThemeFactory::createOne(['themeName'=> 'Loisirs']);
        ThemeFactory::createOne(['themeName'=> 'Culture']);
        ThemeFactory::createOne(['themeName'=> 'Tech']);

        TagFactory::createOne(['tagName' => 'bien-être']);
        TagFactory::createOne(['tagName' => 'pleine conscience']);
        TagFactory::createOne(['tagName' => 'New Age']);
        TagFactory::createOne(['tagName' => 'Bullshit']);
        TagFactory::createOne(['tagName' => 'Intelligence artificielle']);
        TagFactory::createOne(['tagName' => 'Dev informatique']);
        TagFactory::createOne(['tagName' => 'Création artistique']);
        TagFactory::createOne(['tagName' => 'Peinture']);
        TagFactory::createOne(['tagName' => 'Photographie']);


        ArticleFactory::createMany(
            100,
            function (){
                $randomDate = ArticleFactory::faker()->dateTimeBetween('-10 years');
                return [
                    'author' => UserFactory::random(),
                    'theme' => ThemeFactory::random(),
                    'tags' => TagFactory::randomRange(0, 5),
                    'comments' => CommentFactory::new([
                        'createdAt' => ArticleFactory::faker()->dateTimeBetween($randomDate)
                    ])->many(0, 10),
                    'createdAt' => $randomDate,
                    'updatedAt' => ArticleFactory::faker()->dateTimeBetween($randomDate),
                    'publishedAt' => ArticleFactory::faker()->dateTimeBetween($randomDate)
                ];
            }
        );
    }
}
