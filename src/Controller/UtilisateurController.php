<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    #[Route('/listutilisateur', name: 'liste_utilisateur')]
    public function index(): Response
    {
        return $this->render('utilisateur/GererUtilBack.html.twig');
    }



    #[Route('/listadmin', name: 'liste_administrateurs')]
    public function AfficherAdministrateurs(UtilisateurRepository $utilisateurRepository)
    {
        $ListAdmin = $utilisateurRepository->findBy(array('role_util' => 'administrateur'));
        return $this->render('utilisateur/listadminback.html.twig', array("liste_administrateurs" => $ListAdmin));
    }

    #[Route('/listprof', name: 'liste_enseignants')]
    public function AfficherEnseignants(UtilisateurRepository $utilisateurRepository)
    {
        $ListEnseignant = $utilisateurRepository->findBy(array('role_util' => 'enseignant'));
        return $this->render('utilisateur/listenseignantback.html.twig', array("liste_enseignants" => $ListEnseignant));
    }

    #[Route('/listeleve', name: 'liste_eleves')]
    public function AfficherEleves(UtilisateurRepository $utilisateurRepository)
    {
        $ListEleves = $utilisateurRepository->findBy(array('role_util' => 'élève'));
        return $this->render('utilisateur/listeleveback.html.twig', array("liste_eleves" => $ListEleves));
    }



    //NB: ********************* fonction de test ****************
    #[Route('/routetest', name: 'route_test')]
    public function fonction_de_test(UtilisateurRepository $utilisateurRepository)
    {
        $ListAdmin = $utilisateurRepository->findAll();
        return $this->render('utilisateur/test.html.twig', array("liste_administrateurs" => $ListAdmin));
    }
}
