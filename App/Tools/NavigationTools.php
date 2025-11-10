<?php

namespace App\Tools;

class NavigationTools
{
    // Cette fonction sert à retourner la classe CSS "active" pour un élément de menu
    // selon la page en cours (controller + action) afin de surligner l'onglet actif.
    public static function addActiveClass($controller, $action)
    {
        // Vérifie si les paramètres controller et action sont passés dans l'URL
        if (
            isset($_GET['controller']) && $_GET['controller'] === $controller // si le controller dans l'URL correspond à celui attendu
            && isset($_GET['action']) && $_GET['action'] === $action         // et si l'action dans l'URL correspond à celle attendue
        ) {
            return 'active'; // alors on retourne la classe "active" (pour le CSS)
        }
        // Cas spécial : si aucun controller n'est défini dans l'URL
        // et que le menu correspond à la page d'accueil (controller='page', action='home')
        else if (!isset($_GET['controller']) && $controller === 'page' && $action === 'home') {
            return 'active'; // on met "active" sur l'onglet d'accueil
        }

        // Sinon, si aucune condition n'est remplie, on ne retourne rien
        return ''; // pas de classe CSS appliquée
    }
}
