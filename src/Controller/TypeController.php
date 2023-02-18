<?php

namespace App\Controller;

use App\Entity\Type;
use App\Form\TypeActType;
use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry;

class TypeController extends AbstractController
{
    #[Route('/type', name: 'app_type')]
    public function index(): Response
    {
        return $this->render('type/index.html.twig', [
            'controller_name' => 'TypeController',
        ]);
    }

    #[Route('/showtype', name: 'app_showtype')]
    public function afficherType(TypeRepository $repository): Response
    {
        $type = $repository->findAll();
        return $this->render("type/afficherType.html.twig", array("tabType"=>$type));
    }


    #[Route('/addtype', name: 'app_addtype')]
    public function addType(ManagerRegistry $doctrine, Request $request)
    {
        $type = new Type();
        $form = $this->createForm(TypeActType::class, $type);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $em=$doctrine->getManager();
            $em->persist($type);
            $em->flush();
            return $this->redirectToRoute("app_showtype"); 
        }

        return $this->renderForm("type/ajouterType.html.twig", array("formType"=>$form));
    }

    #[Route('/updatetype/{id}', name: 'app_updatetype')]
    public function updateType(TypeRepository $repository, $id, ManagerRegistry $doctrine, Request $request)
    {
        $type = $repository->find($id);
        $form = $this->createForm(TypeActType::class, $type);
        $form->handleRequest($request);
        

        if($form->isSubmitted())
        {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_showtype');
        }

        return $this->renderForm("type/modifierType.html.twig", array("formType"=>$form));
    }

    #[Route('/removetype/{id}', name: 'app_removetype')]
    public function removeType(TypeRepository $repository, $id, ManagerRegistry $doctrine)
    {
        $type = $repository->find($id);
        $em = $doctrine->getManager();
        $em->remove($type);
        $em->flush();
        return $this->redirectToRoute('app_showtype');
    }

}
