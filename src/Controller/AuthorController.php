<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use App\Service\AppService;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(
        AuthorRepository $repository
    ): Response{
        return $this->render('author/index.html.twig', [
            'authorList' => $repository->findAll()
        ]);
    }

    #[Route('/{id}', name: 'author_details', requirements: ['id'=>'\d+'])]
    public function details(int $id, AuthorRepository $repository): Response{

        return $this->render('author/details.html.twig', [
            'id' => $id,
            'author' => $repository->find($id)
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

    #[Route('/form', name: 'author_form')]
    public function form(Request $request, EntityManagerInterface $em): Response{
        // Création de l'entité
        $author = new Author();

        // Création du formulaire
        $form = $this->createForm(
            AuthorType::class,
            $author,
            []
        );

        // hydratation du formulaire
        $form->handleRequest($request);

        // Traitement des données postées
        if($form->isSubmitted() && $form->isValid()){
            // Sauvegarde
            $em->persist($author);
            $em->flush();

            // redirection
            return $this->redirectToRoute('author_home');
        }


        // Affichage de la vue
        return $this->render('author/form.html.twig', [
            'authorForm' => $form->createView()
        ]);
    }

}