<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    #[Route('/utilisateur', name: 'app_utilisateur')]
    public function index(): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

    #[Route('/listadmin', name: 'liste_administrateurs')]
    public function LiteDesAdministrateurs(UtilisateurRepository $utilisateurRepository)
    {
        $ListAdmin = $utilisateurRepository->findAll();
        return $this->render('utilisateur/listadminback.html.twig', array("liste_administrateurs" => $ListAdmin));
    }

    #[Route('/routetest', name: 'route_test')]
    public function fonction_de_test()
    {
        $ListAdmin = NULL;
        return $this->render('utilisateur/test.html.twig', array("liste_administrateurs" => $ListAdmin));
    }
}
