<?php

namespace App\Controller;
// Le namespace indique où se trouve la classe dans l’arborescence du projet.
// Ici, la classe MovieController est dans le dossier "App/Controller".
// Cela permet à PHP de savoir comment charger cette classe automatiquement.

use App\Repository\MovieRepository;
use App\Repository\DirectorRepository;
use App\Repository\GenreRepository;
use Exception;
// Ces lignes importent d’autres classes dont on aura besoin.
// Cela évite d’écrire leurs chemins complets à chaque fois.

class MovieController extends Controller
// La classe MovieController hérite d’une classe "Controller" (probablement une classe de base commune).
// Cela permet d’utiliser des méthodes comme "render()" sans les réécrire ici.
{
    public function route(): void
    // Cette méthode "route()" est appelée pour déterminer quelle action exécuter
    // selon les paramètres reçus dans l’URL (par exemple : ?action=show&id=3)
    {
        try {
            // On entoure le code d’un try/catch pour intercepter les erreurs et afficher une page d’erreur propre.

            if (isset($_GET['action'])) {
                // On vérifie si le paramètre "action" existe dans l’URL.
                // Exemple d’URL : index.php?action=show&id=3

                switch ($_GET['action']) {
                    // On regarde la valeur du paramètre "action" pour décider quoi faire.

                    case 'show':
                        // Si l’action est "show", on appelle la méthode show()
                        $this->show();
                        break;

                    case 'list':
                        $this->list();
                        break;

                    default:
                        // Si l’action demandée n’existe pas, on lance une exception.
                        // Cela permet d’éviter des comportements imprévus.
                        throw new \Exception("Cette action n'existe pas : " . $_GET['action']);
                        break;
                }
            } else {
                // Si aucun paramètre "action" n’est présent dans l’URL,
                // on lève une exception explicite pour signaler le problème.
                throw new \Exception("Aucune action détectée");
            }
        } catch (\Exception $e) {
            // Si une erreur est survenue (exception levée ci-dessus ou autre),
            // on affiche une page d’erreur propre avec le message.
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }

    // Méthode show() : permet d’afficher un film spécifique selon son ID.
    // Elle est "protected" car elle n’a pas vocation à être appelée de l’extérieur,
    // mais seulement à l’intérieur de la classe (par exemple via route()).
    protected function show()
    {
        try {
            // On protège à nouveau ce bloc de code pour gérer les erreurs
            // spécifiques à l’affichage d’un film (ex : ID manquant, film introuvable, etc.)

            if (isset($_GET['id'])) {
                // On vérifie que l’URL contient bien un paramètre "id".
                // Exemple d’URL : index.php?action=show&id=3

                // On crée une instance du repository pour accéder aux données de la table "movie".
                $movieRepository = new MovieRepository();

                // On convertit le paramètre "id" en entier pour éviter les erreurs de type ou les injections SQL.
                $id = (int)$_GET['id'];

                // On appelle la méthode du repository pour récupérer le film correspondant à cet ID.
                $movie = $movieRepository->findOneById($id);

                // Si la méthode a trouvé un film, on affiche la page correspondante.
                if ($movie) {

                    $genreRepository = new GenreRepository();
                    $genres = $genreRepository->findAllByMovieId($movie->getId());

                    $directorRepository = new DirectorRepository();
                    $directors = $directorRepository->findDirectorByMovieId($movie->getId());

                    $this->render('movie/show', [
                        'movie' => $movie, // On envoie l’objet (ou tableau) du film à la vue "movie/show".
                        'genres' => $genres,
                        'directors' => $directors,
                    ]);
                } else {
                    throw new \Exception("Ce film n'existe pas");
                }

                // Si $movie vaut null, rien ne s’affiche (on pourrait ajouter un else ici pour afficher une erreur).

            } else {
                // Si aucun ID n’a été passé dans l’URL, on lève une exception explicite.
                throw new \Exception("L'id est manquant en paramètre d'URL");
            }
        } catch (\Exception $e) {
            // Si une erreur survient (ID manquant, film introuvable, problème PDO, etc.),
            // on affiche la page d’erreur avec le message correspondant.
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }
    protected function list() 
    {
        try {
            $movieRepository = new MovieRepository();
            $movies = $movieRepository->findAll();

            $this->render('movie/list', [
                'movies' => $movies, // On envoie l’objet (ou tableau) du film à la vue "movie/list".
            ]);


        } catch (\Exception $e) {
            // Si une erreur survient (ID manquant, film introuvable, problème PDO, etc.),
            // on affiche la page d’erreur avec le message correspondant.
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
