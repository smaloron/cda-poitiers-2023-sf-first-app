<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/author')]
class AuthorController extends AbstractController
{

    #[Route('/', name: 'author_home')]
    public function index(): Response{
        return $this->render('author/index.html.twig');
    }

    #[Route('/{id}', name: 'author_details', requirements: ['id'=>'\d+'])]
    public function details(int $id): Response{
        return $this->render('author/details.html.twig', ['id' => $id]);
    }

}