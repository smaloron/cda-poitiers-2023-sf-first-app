<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/person', name: 'person_')]
class PersonController extends AbstractController
{

    #[Route('/form', name: 'insert_form')]
    #[Route('/form/{id}', name: 'update_form')]
    public function form(
        Request $request,
        EntityManagerInterface $em,
        Person $person = null){

        if($person === null){
            $person = new Person();
        }


        $form = $this->createForm(
            PersonType::class, $person
        );

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($person);
            $em->flush();
        }

        return $this->render('person/form.html.twig', [
            'personForm' => $form->createView()
        ]);
    }

}