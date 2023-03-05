<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Entity\Utilisateur;
use App\Entity\UtilisateurActivite;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UtilisateurActiviteController extends AbstractController
{
    #[Route('/utilisateur/activite', name: 'app_utilisateur_activite')]
    public function index(): Response
    {
        return $this->render('utilisateur_activite/index.html.twig', [
            'controller_name' => 'UtilisateurActiviteController',
        ]);
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
}
