<?php

namespace App\Repository;

// Importation des classes utilisées
use App\Entity\User;        // La classe User représentant l'entité User (objet métier)
use App\Db\Mysql;           // Probablement une classe gérant la connexion à la base de données
use App\Tools\StringTools;  // Outils pour manipuler des chaînes de caractères (pas utilisé ici)

class UserRepository extends Repository
{
    // Cette méthode cherche un utilisateur par son identifiant
    public function findOneById(int $id)
    {
        // Prépare une requête SQL sécurisée pour éviter les injections SQL
        $query = $this->pdo->prepare("SELECT * FROM user WHERE id = :id");

        // Lie le paramètre :id à la variable $id en précisant le type
        $query->bindParam(':id', $id, $this->pdo::PARAM_STR);

        // Exécute la requête SQL
        $query->execute();

        // Récupère le résultat sous forme de tableau associatif
        $user = $query->fetch($this->pdo::FETCH_ASSOC);

        if ($user) {
            // Si un utilisateur est trouvé, on utilise la méthode statique createAndHydrate
            // pour créer un objet User et remplir ses propriétés depuis le tableau récupéré
            return User::createAndHydrate($user);
        } else {
            // Si aucun utilisateur n'est trouvé, retourne false
            return false;
        }
    }

    // Méthode similaire à findOneById, mais qui cherche par email
    public function findOneByEmail(string $email)
    {
        $query = $this->pdo->prepare("SELECT * FROM user WHERE email = :email");
        $query->bindParam(':email', $email, $this->pdo::PARAM_STR);
        $query->execute();
        $user = $query->fetch($this->pdo::FETCH_ASSOC);

        if ($user) {
            return User::createAndHydrate($user);
        } else {
            return false;
        }
    }

    // Méthode pour sauvegarder ou mettre à jour un utilisateur en base de données
    public function persist(User $user)
    {
        // Vérifie si l'utilisateur a déjà un ID : 
        // si oui, c'est une mise à jour, sinon c'est une insertion
        if ($user->getId() !== null) {
            // Prépare une requête SQL UPDATE
            $query = $this->pdo->prepare(
                'UPDATE user SET first_name = :first_name, last_name = :last_name,  
                 email = :email, password = :password
                 WHERE id = :id'
            );

            // Lie le paramètre :id à l'ID de l'utilisateur
            $query->bindValue(':id', $user->getId(), $this->pdo::PARAM_INT);
        } else {
            // Prépare une requête SQL INSERT
            $query = $this->pdo->prepare(
                'INSERT INTO user (first_name, last_name, email, password, role) 
                 VALUES (:first_name, :last_name, :email, :password, :role)'
            );

            // Lie le rôle de l'utilisateur pour l'insertion
            $query->bindValue(':role', $user->getRole(), $this->pdo::PARAM_STR);
        }

        // Lie les valeurs communes aux deux requêtes : prénom, nom, email, mot de passe
        $query->bindValue(':first_name', $user->getFirstName(), $this->pdo::PARAM_STR);
        $query->bindValue(':last_name', $user->getLastName(), $this->pdo::PARAM_STR);
        $query->bindValue(':email', $user->getEmail(), $this->pdo::PARAM_STR);

        // Hashe le mot de passe avant de le stocker pour la sécurité
        $query->bindValue(':password', password_hash($user->getPassword(), PASSWORD_DEFAULT), $this->pdo::PARAM_STR);

        // Exécute la requête (INSERT ou UPDATE selon le cas)
        return $query->execute();
    }
}
