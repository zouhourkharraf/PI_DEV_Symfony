<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/home', name: 'page_acceuil')]
    public function index(): Response
    {
        return $this->render('FrontOfficeVisiteur.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

    #[Route('/homeuser', name: 'page_utilisateur_connecte')]
    public function acceuil_utilisateur()
    {
        return $this->render('FrontOfficeConnecte.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }
    #[Route('/backoffice', name: 'page_acceuil_back_office')]
    public function acceuil_back_office()
    {
        return $this->render('BackOffice.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }
}
