<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Repository\PublisherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/publisher', name: 'publisher_')]
class PublisherController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(PublisherRepository $repository): Response
    {
        return $this->render('publisher/index.html.twig', [
            'publisherList' => $repository->findAll()
        ]);
    }

    #[Route('/new/{name}/{city}', name: 'new')]
    public function newPublisher(string $name, string $city,
                                EntityManagerInterface $em): Response {
        $publisher = new Publisher();
        $publisher->setName($name)->setCity($city);

        $em->persist($publisher);
        $em->flush();

        return $this->redirectToRoute('publisher_home');
    }
}
