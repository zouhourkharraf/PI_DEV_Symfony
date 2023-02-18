<?php

namespace App\Controller;

use App\Entity\Don;
use App\Entity\Evenement;
use App\Form\DonType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EvenementRepository;
use App\Repository\DonRepository;


class DonController extends AbstractController
{
    #[Route('/don', name: 'app_don')]
    public function index(): Response
    {
        return $this->render('don/index.html.twig', [
            'controller_name' => 'DonController',
        ]);
    }

    #[Route('/adddon', name: 'app_adddon')]
    public function addDon(\Doctrine\Persistence\ManagerRegistry $doctrine, Request $request)
    {
        $don = new Don();
        $form = $this->createForm(DonType::class, $don);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($don);
            $em->flush();
            return $this->redirectToRoute("app_adddon");
        }
        return $this->renderForm("don/addDon.html.twig",
            array("formDon" => $form));
    }

    #[Route('/dons', name: 'app_dons')]
    public function listdon(DonRepository $repository): Response
    {
        $dons = $repository->findAll();
        return $this->render("don/listdons.html.twig", array("tabdons" => $dons));
    }

    #[Route('/updateDon/{id}', name: 'app_updateDon')]
    public function updateDon(DonRepository $repository, $id, ManagerRegistry $doctrine, Request $request)
    {
        $don = $repository->find($id);
        $form = $this->createForm(DonType::class, $don);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute("app_dons");
        }
        return $this->renderForm('Don/update_don.html.twig', ['form' => $form]);

    }

    #[Route('/removeDon/{id}', name: 'app_removeDon')]
    public function deleteDon(ManagerRegistry $doctrine,$id,DonRepository $repository)
    {
        $don= $repository->find($id);
        $em= $doctrine->getManager();
        $em->remove($don);
        $em->flush();
        return $this->redirectToRoute("app_dons");

    }
}
