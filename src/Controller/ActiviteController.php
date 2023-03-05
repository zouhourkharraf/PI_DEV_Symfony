<?php

namespace App\Controller;
use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Entity\Utilisateur;
use App\Repository\ActiviteRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;



    

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
        return $this->render("activite/backoffice/affichTest.html.twig", array("tabActivite"=>$activite));
    }
    


    #[Route('/showactivitefront', name: 'app_showactivitefront')]
    public function showAcivityFront(ActiviteRepository $repository): Response
    {
        $activite = $repository->findAll();
        return $this->render("activite/frontoffice/affichTest.html.twig", array("tabActivite"=>$activite));
    }

    #[Route('/addactivite', name: 'app_addactivite')]
    public function addActivity(ManagerRegistry $doctrine, Request $request)
    {
        $activite = new Activite();
        $form=$this->createForm(ActiviteType::class,$activite);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$doctrine->getManager();
            $em->persist($activite);
            $em->flush();
            return $this->redirectToRoute("app_showactivite");
        }
        return $this->renderForm("activite/backoffice/ajouterAct.html.twig", array("formActivite"=>$form));
    }

    #[Route('/updateactivite/{id}', name: 'app_updateactivite')]
    public function updateActivity(ActiviteRepository $repository, $id, ManagerRegistry $doctrine, Request $request)
    {
        $activite = $repository->find($id);
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_showactivite');
        }

       return $this->renderForm("activite/backoffice/modifierAct.html.twig", array("formActivite"=>$form));
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

   
  #[Route('/participeractivite', name: 'app_participeractivite')]
   
  public function participer(Request $request, Activite $activite, ManagerRegistry $doctrine): Response
  {
    
    $user = $this->getUser();
    if (!$user instanceof Utilisateur) {
        throw new AccessDeniedException();
    }
    else if($user->getRoleUtil()=="eleve")
    {
        $entityManager = $doctrine->getManager();
        $activite->addListeUtilisateur($user);
        $nbparticipant = $activite->getNbParticipants();
        $nbparticipant--;
        $activite->setNbParticipants($nbparticipant);
        $entityManager->persist($activite);
        $entityManager->flush();

        return $this->redirectToRoute('app_showactivite');
    }
    
    
  }
/*
  
  #[Route('/participer/{idActivite}/{idUtilisateur}', name: 'app_participer')]
 
    public function participerActivite($idActivite, $idUtilisateur, ManagerRegistry $doctrine,ActiviteRepository $repositoryact, UtilisateurRepository $repositoryuser): Response
    {
        // Récupération des entités Utilisateur et Activite
        //$entityManager = $this->getDoctrine()->getManager();
        $entityManager = $doctrine->getManager();
        //$activite = $entityManager->getRepository(Activite::class)->find($idActivite);
       // $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($idUtilisateur);

       $activite = $repositoryact->find($idActivite);
       $utilisateur = $repositoryuser->find($idUtilisateur);

        // Vérification que les entités existent
        if (!$activite) {
            throw $this->createNotFoundException('L\'activité n\'existe pas.');
        }
        if (!$utilisateur) {
            throw $this->createNotFoundException('L\'utilisateur n\'existe pas.');
        }

        // Ajout de l'utilisateur à l'activité
        //$activite->addUtilisateur($utilisateur);
        if($utilisateur->getRoleUtil()=="eleve")
        {
            $activite->addListeUtilisateur($utilisateur);
            $nb = $activite->getNbParticipants();
            $nb--;
            $activite->setNbParticipants($nb);
        $entityManager->flush();

        // Réponse HTTP
       return new Response('L\'utilisateur a été ajouté à l\'activité.');
       
      
       return $this->redirectToRoute('app_showactivitefront');
       
        }
        else return new Response('L\'utilisateur n\'est pas un eleve');

    }
    
 */ 

 #[Route('/participer/{idActivite}/{idUtilisateur}', name: 'app_participer')]
 
    public function participerActivite($idActivite, $idUtilisateur, ManagerRegistry $doctrine,ActiviteRepository $repositoryact, UtilisateurRepository $repositoryuser): Response
    {
        // Récupération des entités Utilisateur et Activite
        //$entityManager = $this->getDoctrine()->getManager();
        $entityManager = $doctrine->getManager();
        //$activite = $entityManager->getRepository(Activite::class)->find($idActivite);
       // $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($idUtilisateur);

       $activite = $repositoryact->find($idActivite);
       $utilisateur = $repositoryuser->find($idUtilisateur);

        // Vérification que les entités existent
        if (!$activite) {
            throw $this->createNotFoundException('L\'activité n\'existe pas.');
        }
        if (!$utilisateur) {
            throw $this->createNotFoundException('L\'utilisateur n\'existe pas.');
        }

        if ($activite->getListeUtilisateurs()->contains($utilisateur)) {
            //$this->addFlash('warning', 'L\'utilisateur est déjà inscrit à cette activité.');
            //return $this->redirectToRoute('app_showactivitefront');
            return new Response('warning L\'utilisateur est déjà inscrit à cette activité');
        }
        // Ajout de l'utilisateur à l'activité
        //$activite->addUtilisateur($utilisateur);
        if($activite->getNbParticipants()<1)
        {
            return new Response('pas de place disponile');
        }
        if($utilisateur->getRoleUtil()=="eleve")
        {
            $activite->addListeUtilisateur($utilisateur);
            $nb = $activite->getNbParticipants();
            $nb--;
            $activite->setNbParticipants($nb);
        $entityManager->flush();

        // Réponse HTTP
       return new Response('L\'utilisateur a été ajouté à l\'activité.');
      // $this->addFlash('success', 'L\'utilisateur a été ajouté à l\'activité.');
       
      
       return $this->redirectToRoute('app_showactivitefront');
       
        }
        
        else
        {
            //$this->addFlash('danger', 'L\'utilisateur n\'est pas un élève');
            //return $this->redirectToRoute('app_showactivitefront');
            return new Response('L\'utilisateur n\'est pas un eleve');

        } 
    }

    #[Route('/statistiques', name: 'app_statistiques')]
public function statistiques(ActiviteRepository $activiteRepository, UtilisateurRepository $utilisateurRepository): Response
{
    // Récupération de tous les utilisateurs ayant un rôle "élève"
    $eleves = $utilisateurRepository->findBy(['role_util' => 'eleve']);
    

    // Nombre total d'activités
    $nbActivites = $activiteRepository->count([]);

    // Nombre d'élèves ayant participé à au moins une activité
    $nbElevesParticipes = 0;

    foreach ($eleves as $eleve) {
        // Comptage du nombre d'activités auxquelles l'élève a participé
        $nbActivitesParticipees = count($eleve->getListeActivites());

        if ($nbActivitesParticipees > 0) {
            $nbElevesParticipes++;
        }
    }

    // Calcul de la statistique
    $statistique = 0;

    if ($nbElevesParticipes > 0) {
        $statistique = round(($nbElevesParticipes / count($eleves)) * 100, 2);
    }

    // Affichage de la statistique
    return $this->render('activite/backoffice/statistiques.html.twig', [
        'nbActivites' => $nbActivites,
        'nbElevesParticipes' => $nbElevesParticipes,
        'statistique' => $statistique,
    ]);
}

 
}


