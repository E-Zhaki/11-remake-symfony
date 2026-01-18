<?php
// Contact.php, c’est La structure de la table
// Les règles métier
// La validation
// Le lien entre formulaire et base

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
// ORM → sert à créer la table et les colonnes
// Assert → sert à valider les données du formulaire
// UniqueEntity → sert à empêcher les doublons avant l’insertion
// ➡️ Doctrine = base de données
// ➡️ Validator = formulaire

#[UniqueEntity('email', message: "Cet email appartient déjà à l'un de vos contacts.")]
#[UniqueEntity('phone', message: "Ce numéro de téléphone appartient déjà à l'un de vos contacts.")]

// Vérifie avant le flush
// Évite l’erreur SQL
// Permet d’afficher un message propre dans le formulaire
// ➡️ C’est une sécurité côté Symfony, pas SQL.

#[ORM\Entity(repositoryClass: ContactRepository::class)]
//  Indique à Doctrine :
// “Cette classe doit être stockée en base”
// “Elle utilise ce repository”
// ➡️ Sans ça : pas de table, pas de CRUD

class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    // Clé primaire
    // Auto-incrémentée
    // Tu n’y touches jamais


    #[Assert\NotBlank (message: "Le prénom est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le prénom doit contenir au maximum {{ limit }} caractères.",
    )]
    #[ORM\Column(length: 255)]
    private ?string $firstName = null;
    // Assert → règles du formulaire
    // ORM\Column → règle de la base
    // string → type PHP
    // null → valeur par défaut (objet vide)
    // ➡️ Formulaire + Base + PHP réunis ici



    #[Assert\NotBlank (message: "Le nom est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le nom doit contenir au maximum {{ limit }} caractères.",
    )]
    #[ORM\Column(length: 255)]
    private ?string $lastName = null;



    #[Assert\NotBlank (message: "L'email est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "L'email' doit contenir au maximum {{ limit }} caractères.",
    )]
     #[Assert\Email(
        message: "L'email {{ value }} est invalide.",
    )]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;




    #[Assert\NotBlank (message: "Le numéro de téléphone est obligatoire.")]
    #[Assert\Length(
        min: 7,
        max: 20,
        minMessage: "Le numéro de téléphone doit contenir au minimum {{ limit }} caractères.",
        maxMessage: "Le numéro de téléphone doit contenir au maximum {{ limit }} caractères.",
    )]
    #[Assert\Regex(
        pattern: '/^[0-9 -+]+$/',
        match: true,
        message: 'Le numéro de téléphone est invalide.',
    )]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $phone = null;
    // unique: true = contrainte SQL
    // UniqueEntity = validation Symfony
    // ➡️ Toujours les deux ensemble (bonne pratique)


    #[Assert\Length(
        max: 1000,
        maxMessage: "Le commentaire doit contenir au maximum {{ limit }} caractères.",
    )]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;
    // TEXT = texte long
    // nullable: true = pas obligatoire
    // Pas limité comme un VARCHAR


    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;
    // 👉 À retenir :
    // Doctrine ne les remplit pas tout seul
    // C’est toi qui fais :


    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    // 👉 À comprendre absolument :
    // Symfony Form utilise ces méthodes
    // Doctrine lit et écrit via ces méthodes
    // Tu ne manipules jamais les propriétés directement
    // ➡️ C’est la porte d’entrée des données


    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
