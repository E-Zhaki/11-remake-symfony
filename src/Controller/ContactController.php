<?php

namespace App\Controller;

use COM;
use App\Entity\Contact;
use App\Form\ContactFormType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Dom\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ContactController extends AbstractController
{
    #[Route('/', name: 'app_contact_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig', [
        ]);
    }


    #[Route('/contact/create', name: 'app_contact_create', methods:['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response {

        $contact = new Contact();

        $form = $this->createForm(ContactFormType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ){

        $contact->setCreatedAt(new DateTimeImmutable());
        $contact->setUpdatedAt(new DateTimeImmutable());

         $entityManager->persist($contact);
         $entityManager->flush();

         $this->addFlash('success', "Le contact a été ajouté à la liste.");

         return $this->redirectToRoute('app_contact_index');

        }
        

        return $this->render('contact/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

}