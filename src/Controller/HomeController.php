<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController
{
    function index(): Response{
        return new Response("Hello Symfony");
    }


    #[Route('/home2', name: 'another_home')]
    #[Route('/home3', name: 'again_another_home')]
    function anotherIndex(): Response {
        return new Response('Hello again');
    }

    #[Route('/details/{id}/{name}',
        name: 'details',
        requirements: ['id' => '\d+'],
        defaults: ['id' => 1, 'name' => 'Olive'],
        methods: ['POST']
    )]
    public function details(string $name, int $id = 1) : Response{
        return new Response("Bonjour $name votre id est $id");
    }
}