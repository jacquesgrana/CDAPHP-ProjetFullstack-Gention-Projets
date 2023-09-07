<?php
namespace Jacques\ProjetPhpGestionProjets\Controller;

use Jacques\ProjetPhpGestionProjets\Kernel\View;
use Jacques\ProjetPhpGestionProjets\Kernel\AbstractController;
use Jacques\ProjetPhpGestionProjets\Kernel\Securite;
use Jacques\ProjetPhpGestionProjets\Utils\TacheDB;
use Jacques\ProjetPhpGestionProjets\Entity\Projet as ProjetObj;

class Projet extends AbstractController {
    private string $mode;
    private ProjetObj $projet; 
    //private array $taches;
    private array $tachesAll;
    private string $titlePage = 'Page d\'un projet';

    public function index()
    {
        
        if($this->mode !== 'create') {
            //$this->taches = TacheDB::getByProjetId($this->projet->getId_projet());
            $this->tachesAll = TacheDB::getAllByProjetId($this->projet->getId_projet());
        }
        $view = new View();
        $view->setHead('head.html')
        ->setHeader('header.html')
        ->setMain('projet.php')
        ->setFooter('footer.html');
        $view->render([
            'flash' => $this->getFlashMessage(),
            'titlePage' => $this->titlePage,
            'windowName' => 'Gestion de Projets - Projet',
            'projet' => $this->projet ?? null,
            //'taches' => $this->taches,
            'tachesAll' => $this->tachesAll ?? [],
            'mode' => $this->mode,
            'isConnected' => Securite::isConnected()
        ]);
    }

    public function edit() {
        if (Securite::isConnected()) {
            if(isset($_GET['id'])) {
                $id_projet = $_GET['id'];
                $this->projet = ProjetObj::getById($id_projet);
                $_SESSION['id_projet'] = $id_projet;
            }            
        }
        $this->mode = 'edit';
        $_SESSION['mode_projet'] = $this->mode;
        $this->titlePage = 'Page d\'édition d\'un projet';
        $this->index();
    }

    public function view() {
        if (Securite::isConnected()) {
            if(isset($_GET['id'])) {
                $id_projet = $_GET['id'];
                $this->projet = ProjetObj::getById($id_projet);
                $_SESSION['id_projet'] = $id_projet;
            }            
        }
        $this->mode = 'view';
        $_SESSION['mode_projet'] = $this->mode;
        $this->titlePage = 'Page de consultation d\'un projet';
        $this->index();
    }

    public function create() {
        //$this->projet = null;
        $this->mode = 'create';
        $_SESSION['mode_projet'] = $this->mode;
        $this->titlePage = 'Page de création d\'un projet';
        $this->index();
    }
}
?>