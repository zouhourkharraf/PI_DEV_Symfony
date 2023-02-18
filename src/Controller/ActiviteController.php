<?php

namespace App\Controller;
use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActiviteController extends AbstractController
{
    #[Route('/activite', name: 'app_activite')]
    public function index(): Response
    {
        return $this->render('activite/index.html.twig', [
            'controller_name' => 'ActiviteController',
        ]);
    }

    #[Route('/showactivite', name: 'app_showactivite')]
    public function ShowActivity(ActiviteRepository $repository): Response
    {
        $activite = $repository->findAll();
        return $this->render("activite/affichTest.html.twig", array("tabActivite"=>$activite));
    }

    #[Route('/addactivite', name: 'app_addactivite')]
    public function addActivity(ManagerRegistry $doctrine, Request $request)
    {
        $activite = new Activite();
        $form=$this->createForm(ActiviteType::class,$activite);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $em=$doctrine->getManager();
            $em->persist($activite);
            $em->flush();
            return $this->redirectToRoute("app_showactivite");
        }
        return $this->renderForm("activite/ajouterAct.html.twig", array("formActivite"=>$form));
    }

    #[Route('/updateactivite/{id}', name: 'app_updateactivite')]
    public function updateActivity(ActiviteRepository $repository, $id, ManagerRegistry $doctrine, Request $request)
    {
        $activite = $repository->find($id);
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_showactivite');
        }

       return $this->renderForm("activite/modifierAct.html.twig", array("formActivite"=>$form));
    }

    #[Route('/removeactivite/{id}', name: 'app_removeactivite')]
    public function removeActivity(ActiviteRepository $repository, $id, ManagerRegistry $doctrine)
    {
        $activite = $repository->find($id);

        $em = $doctrine->getManager();
        $em->remove($activite);
        $em->flush();
        return $this->redirectToRoute('app_showactivite');
    }
}
