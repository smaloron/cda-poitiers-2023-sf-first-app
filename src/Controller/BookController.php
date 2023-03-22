<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book', name: 'book_')]
class BookController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(BookRepository $repository){
        return $this->render(
            'book/index.html.twig',
            ['bookList'=> $repository->findAll()]
        );
    }

    #[Route('/form', name: 'insert_form')]
    public function form(Request $request, EntityManagerInterface $em){
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($book);
            $em->flush();
        }

        return $this->render(
            'book/form.html.twig',
            ['bookForm' => $form->createView()]
        );
    }

}