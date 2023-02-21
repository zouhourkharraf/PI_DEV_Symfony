<?php

namespace App\Controller;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\FormationRepository;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="office_front")
     */
    public function index(): Response
    {
        return $this->render('FrontOfficeVisiteur.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }
    /**
     * @Route("/backoffice", name="accueil_back_office")
     */
    public function accueil_back_office(): Response
    {
        return $this->render('BackOffice.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }
    /**
     * @Route("/formation/List", name="get_FormationList")
     */
    public function list(FormationRepository $repo): Response
    {
        $formations = $repo->findAll();
        //dd($formations);
        return $this->render('/formation/ListFormation.html.twig', [
            'formations' => $formations
        ]);
    }
    /**
     * @Route("/formation/see_format/{id}", name="show_formation")
     */
    public function show_format(ManagerRegistry $doctrine, int $id): Response
    {
        $formation = $doctrine->getRepository(Formation::class)->find($id);

        if (!$formation) {
            throw $this->createNotFoundException(
                'No formation found for id ' . $id
            );
        }
        return $this->render('/formation/det_formation.html.twig', [
            'formation' => $formation
        ]);
    }
}
