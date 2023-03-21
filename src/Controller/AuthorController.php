<?php

namespace App\Controller;

use App\Service\AppService;
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

}