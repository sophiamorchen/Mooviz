<?php

namespace App\Entity; // Déclare le namespace pour organiser le code

use App\Tools\StringTools; // Importation d'un outil pour manipuler les chaînes (ex: convertir en PascalCase)

class Entity
{
    /**
     * Crée une instance de l'entité et l'hydrate avec un tableau de données
     *
     * @param array $data Données à hydrater
     * @return static Retourne une instance de la classe enfant
     */
    public static function createAndHydrate(array $data): static
    {
        // "static" ici fait référence à la classe qui hérite de Entity
        // alors que "self" ferait référence uniquement à Entity
        $entity = new static();

        // Appelle la méthode hydrate pour remplir les propriétés de l'objet
        $entity->hydrate($data);

        // Retourne l'objet complètement initialisé
        return $entity;
    }

    /**
     * Remplit les propriétés de l'entité avec les données fournies
     *
     * @param array $data Tableau associatif clé => valeur
     */
    public function hydrate(array $data)
    {
        // Vérifie qu'il y a des données à traiter
        if (count($data) > 0) {
            // Parcourt toutes les entrées du tableau
            foreach ($data as $key => $value) {

                // Construit le nom du setter correspondant à la clé
                // ex: 'first_name' devient 'setFirstName'
                $methodName = 'set' . StringTools::toPascalCase($key);

                // Vérifie si la méthode existe réellement dans la classe
                if (method_exists($this, $methodName)) {

                    // Gestion spéciale pour certaines clés qui sont des dates
                    // On convertit la valeur en objet DateTime
                    if ($key == 'created_at') {
                        $value = new \DateTime($value);
                    } else if ($key == 'release_date') {
                        $value = new \DateTime($value);
                    } else if ($key == 'duration') {
                        $value = new \DateTime($value);
                    }

                    // Appelle dynamiquement le setter correspondant
                    // ex: $this->setFirstName($value)
                    $this->{$methodName}($value);
                }
            }
        }
    }
}
