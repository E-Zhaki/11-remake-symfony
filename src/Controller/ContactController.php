<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ContactController extends AbstractController
{
    #[Route('/', name: 'app_contact_index', methods: ['GET'])]
        // Route principale de la page d'accueil
        // URL : /
        // Nom de la route : app_contact_index
        // Méthode HTTP : GET (on lit des données, on n'en modifie pas)
        public function index(ContactRepository $contactRepository): Response
        {
            // Symfony injecte automatiquement le ContactRepository
            // Ce repository sert à lire les données de la table contact

            $contacts = $contactRepository->findAll();
            // findAll() récupère TOUS les contacts depuis la base de données
            // Doctrine transforme chaque ligne SQL en objet Contact

            return $this->render('contact/index.html.twig', [
                "contacts" => $contacts
                // On envoie la liste des contacts à la vue Twig
            ]);
    }


 
   #[Route('/contact/create', name: 'app_contact_create', methods:['GET', 'POST'])]
        // Déclare une route accessible à l’URL /contact/create
        // Elle porte le nom "app_contact_create"
        // Elle accepte deux méthodes HTTP :
        // - GET → afficher le formulaire
        // - POST → envoyer le formulaire

    public function create(Request $request, EntityManagerInterface $entityManager): Response {
        // Méthode appelée quand on visite /contact/create
        // $request contient les données envoyées par le navigateur (GET ou POST)
        // $entityManager sert à communiquer avec la base de données via Doctrine
        // La méthode doit retourner une Response (une page HTML par exemple)

            $contact = new Contact();
            // Création d’un nouvel objet Contact
            // À ce stade, l’objet est vide (aucune donnée saisie)
            // Il respecte déjà la structure et les règles définies dans l’entité Contact

            $form = $this->createForm(ContactFormType::class, $contact);
            // Création du formulaire Symfony
            // ContactFormType décrit les champs du formulaire
            // Le formulaire est lié à l’objet $contact
            // Symfony saura remplir automatiquement $contact avec les données du formulaire

            $form->handleRequest($request);
            // Symfony analyse la requête HTTP
            // Si c’est un POST :
            //  - il récupère les données envoyées
            //  - il remplit l’objet $contact
            //  - il prépare la validation
            // Si c’est un GET : il ne fait rien

            if ($form->isSubmitted() && $form->isValid() ){
            // Vérifie deux choses :
            // 1) Le formulaire a bien été envoyé (POST)
            // 2) Les données respectent toutes les règles (Assert, UniqueEntity, etc.)

                $contact->setCreatedAt(new DateTimeImmutable());
                // Définit la date de création du contact
                // DateTimeImmutable = date actuelle, non modifiable
                // Symfony ne le fait pas automatiquement, on doit le faire nous-mêmes

                $contact->setUpdatedAt(new DateTimeImmutable());
                // Définit la date de dernière modification
                // Ici, création = modification, donc même date

                $entityManager->persist($contact);
                // Indique à Doctrine qu’il doit enregistrer cet objet
                // Aucune requête SQL n’est encore exécutée

                $entityManager->flush();
                // Exécute réellement la requête SQL
                // Doctrine transforme l’objet $contact en INSERT INTO contact (...)

                $this->addFlash('success', "Le contact a été ajouté à la liste.");
                // Crée un message temporaire (flash message)
                // Il sera affiché sur la page suivante
                // Ici, message de succès pour l’utilisateur

                return $this->redirectToRoute('app_contact_index');
                // Redirige vers la page de la liste des contacts
                // Évite le renvoi du formulaire
                // Permet d’afficher le message flash
            }

            return $this->render('contact/create.html.twig', [
                "form" => $form->createView()
            ]);
            // Si le formulaire n’est pas envoyé ou contient des erreurs :
            // - on affiche la page create.html.twig
            // - on envoie la version "vue" du formulaire à Twig
            // Twig s’occupe de l’affichage HTML;
        }


    #[Route('/contact/edit/{id}', name: 'app_contact_edit', methods:['GET'])]
        //  Déclare une route Symfony
        //  URL : /contact/edit/ID
        // {id} est un paramètre dynamique (ex: /contact/edit/3)
        // name = nom interne de la route (utilisé dans Twig avec path())
        // methods = ['GET'] car on affiche juste le formulaire pour l’instant

        public function edit(int $id, ContactRepository $contactRepository): Response
        //  Méthode appelée quand on va sur la route
        //  $id : récupère automatiquement l'id depuis l'URL
        // ContactRepository : injection automatique du repository (Doctrine)
        //  Response : la fonction doit retourner une réponse HTTP
        {

            $contact = $contactRepository->find($id);
            //  On va chercher en base de données le contact
            //  WHERE id = $id
            //  $contact est soit :
            //    - un objet Contact (s'il existe)
            //    - null (s'il n'existe pas)

            if (null === $contact) {
                throw new NotFoundHttpException("Ce contact n'existe pas");
            }

            return $this->render("/contact/edit.html.twig");

        }


        }