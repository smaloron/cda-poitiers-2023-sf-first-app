<?php

namespace App\Controller;

use App\Entity\Author;
use App\Service\AppService;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/author')]
class AuthorController extends AbstractController
{
    private AppService $service;

    /**
     * @param AppService $service
     */
    public function __construct(AppService $service)
    {
        $this->service = $service;
    }


    #[Route('/', name: 'author_home')]
    public function index(): Response{
        return $this->render('author/index.html.twig', [
            'authorList' => $this->service->getAuthorList()
        ]);
    }

    #[Route('/{id}', name: 'author_details', requirements: ['id'=>'\d+'])]
    public function details(int $id): Response{

        dump($this->service);

        return $this->render('author/details.html.twig', [
            'id' => $id,
            'author' => $this->service->getAuthor($id)
        ]);
    }


    #[Route('/new/{firstName}/{lastName}')]
    public function newAuthor(
        string $firstName,
        string $lastName,
        EntityManagerInterface $manager) : Response{

        //Instance de Faker
        $faker = Factory::create();

        // Instanciation de l'entité
        $author = new Author();
        // Hydratation de l'entité
        // avec les paramètres de la route
        $author
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setBio($faker->realText(2000));

        dump($author);

        // Sauvegarde de l'auteur avec Doctrine

        // Préparation de l'opération
        $manager->persist($author);
        // execution de l'opération
        $manager->flush();

        return $this->render('author/new.html.twig', ['author' => $author]);
    }

}