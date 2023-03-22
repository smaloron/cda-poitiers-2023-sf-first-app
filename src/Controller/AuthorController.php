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

    #[Route('/form', name: 'author_insert_form')]
    #[Route('/form/{id}',
            name: 'author_update_form',
            requirements: ['id'=>'\d+'])]
    public function form(
        Request $request,
        EntityManagerInterface $em,
        Author $author = null
    ): Response{
        // Création de l'entité
        $actionName = 'modification';
        if($author === null){
            $author = new Author();
            $actionName = 'ajout';
        }

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

            $this->addFlash('success', "votre $actionName est un succès");

            // redirection
            return $this->redirectToRoute('author_home');
        }


        // Affichage de la vue
        return $this->render('author/form.html.twig', [
            'authorForm' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'author_delete', requirements: ['id'=> '\d+'])]
    public function delete(int $id, AuthorRepository $repository){
        try {
            $author = $repository->find($id);
            $authorName = $author->getLastName();

            $repository->remove($author, true);

            $this->addFlash('success', "Cet auteur $authorName a été supprimé");

            return $this->redirectToRoute('author_home');
        } catch (\Throwable $ex){
            $this->addFlash('error', "Impossible de trouver l'auteur à supprimer");
            return $this->redirectToRoute('author_home');
        }
    }

}