<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    function index(): Response{
        return $this->render('home.html.twig');
    }


    #[Route('/home2', name: 'another_home')]
    #[Route('/home3', name: 'again_another_home')]
    function anotherIndex(): Response {
        return new Response('Hello again');
    }

    #[Route('/details/{id}/{name}',
        name: 'details',
        requirements: ['id' => '\d+'],
        defaults: ['id' => 1, 'name' => 'Olive']
    )]
    public function details(string $name, int $id = 1) : Response{
        $product = new \stdClass();
        $product->price = 10000000;
        $product->name = 'âme';

        return $this->render(
            'home/details.html.twig',
            [
                'name'=> $name, 'id'=>$id,
                'person' => ['name' => 'Hugo', 'firstName' => 'Victor'],
                'scores' => [1,6,8,3],
                'prod' => $product
            ]);
    }
}