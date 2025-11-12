<?php 

namespace App\Controller;

use App\Repository\MovieRepository;
use App\Entity\Movie;
use Exception;

class MovieController extends Controller
{
    public function route(): void
    {
        try {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'show':
                        $this->show();
                        break;
                    case 'delete':
                        // Appeler méthode delete()
                        break;
                    default:
                        throw new \Exception("Cette action n'existe pas : " . $_GET['action']);
                        break;
                }
            } else {
                throw new \Exception("Aucune action détectée");
            }
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }

    // ici on met en protected car c'est "lui même" qui s'appelle
    protected function show() 
    {
        try{
            if(isset($_GET['id'])){
                // Récupérer le film avec le repository
                $movieRepository = new MovieRepository();
                $id = (int)$_GET['id'];
                $movie = $movieRepository->findOneById($id);
                var_dump($movie);

                $this->render('movie/show', [
                    'movie' => $movie,
                    ]);
            } else {
                throw new \Exception("L'id est manquant en paramètre d'URL");
            }
            

        } catch(\Exception $e){
            $this->render('errors/default', [
                'error' => $e->getMessage()

            ]);
        }
    }
}


