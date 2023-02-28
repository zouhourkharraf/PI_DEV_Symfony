<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\AjouterMatiereType;
use Doctrine\DBAL\Schema\View;
use App\Form\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use SebastianBergmann\CodeCoverage\Report\Html\Renderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class MatiereController extends AbstractController
{
    #[Route('/matiere', name: 'app_matiere')]
    public function index(): Response
    {
        return $this->render('matiere/index.html.twig', [
            'controller_name' => 'MatiereController',
        ]);
    }
    ///////////Ajouter/////////////////
    #[Route('/AjouterMatiere', name: 'AjouterMatiere1')]
    public function AjouterMatiere(ManagerRegistry $doctrine,Request $request): Response
    { 
        $Categorie= new Categorie();
        $form = $this->createForm(AjouterMatiereType::class,$Categorie);
        $form->handleRequest($request);
      
        if ($form->isSubmitted()) {
            $em = $doctrine->getManager();
            $em->persist($Categorie);
            $em->flush();
            return $this->redirectToRoute("app_aff");
        }
        return $this->renderForm("matiere/AjouterMatiere.html.twig",array("AjouterMatiere"=> $form));

    }
    //////////////////////////afficher///////////////////////
    #[Route('/AfficherMatiére', name: 'app_aff')]
      public function liste (ManagerRegistry $mg): Response
      {
          $repo=$mg->getRepository(Categorie::class);
          $resultat = $repo ->FindAll();
          return $this->render('matiere/afficher.html.twig', [
              'matiere' => $resultat,
          ]);
      } 
//////////////////////////modifier////////////////////////
      #[Route('/UpdateMatiere/{id}', name: 'app_update')]
       public function updateType(ManagerRegistry $doctrine,CategorieRepository $CategorieRepository,$id,Request $request): Response
       {
      $Categorie=$CategorieRepository->find($id);
      $form=$this->CreateForm(AjouterMatiereType :: class,$Categorie);
      $form->handleRequest($request);
      if($form->isSubmitted())
      {
      $em=$doctrine->getManager();
      $em->persist($Categorie);
      $em->flush();
    return $this->redirectToRoute('app_aff');
      }
      return $this->renderForm("matiere/AjouterMatiere.html.twig",array("AjouterMatiere"=>$form));
   
       }

//////////////////////////supprimer////////////////////////
#[Route('/RemoveMatiere/{id}', name: 'app_remove')]
public function remove (ManagerRegistry $mg ,CategorieRepository $X , $id): Response
{    
  
  $Categorie= $X->find($id);
  $em=$mg->getManager();
  $em->remove($Categorie);
  $em->flush();
   return $this->redirectToRoute('app_aff');
}       

/////////////Affichereleve///////////:
#[Route('/AfficherElevee', name: 'app_affeleve')]
public function listeeleve (ManagerRegistry $mg): Response
{
    $repo=$mg->getRepository(Categorie::class);
    $resultat = $repo ->FindAll();
    return $this->render('matiere/affichageEleve.html.twig', [
        'matiere' => $resultat,
    ]);
} 



   

}
