<?php

namespace App\Controller;

use COM;
use App\Entity\Contact;
use App\Form\ContactFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\form;

final class ContactController extends AbstractController
{
    #[Route('/', name: 'app_contact_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig', [

        ]);
    }


    #[Route('/contact/create', name: 'app_contact_create', methods:['GET', 'POST'])]
    public function create(Request $request): Response {

        $contact = new Contact();

        $form = $this->createForm(ContactFormType::class, $contact);

        $form->handleRequest($request)
        

        return $this->render('contact/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

}