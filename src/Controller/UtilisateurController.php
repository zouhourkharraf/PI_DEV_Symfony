<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\AjouterEleveType;
use App\Form\AjouterUtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{

    // ****************** Affichage Back Office ***********************************
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

    // ****************** FIN Affichage Back Office ***********************************


    // **************************** Ajout Front Office *********************************

    #[Route('/AjoutEnseignant', name: 'ajouter_enseignant')]
    public function AjouterEnseignant(ManagerRegistry $doctrine, Request $request)
    {
        $enseignant = new Utilisateur(); //création de l'objet $enseignant de type Utilisateur
        $FromEnseignant = $this->createForm(AjouterUtilisateurType::class, $enseignant); //création du formulaire associé à l'objet $enseignant
        $FromEnseignant->handleRequest($request); //réccupérer le formulaire envoyé dans la requête 

        if ($FromEnseignant->isSubmitted() && $FromEnseignant->isValid()) //si le formulaire est soumis
        {
            //dd($FromEnseignant->getData());
            $em = $doctrine->getManager();
            $em->persist($enseignant);
            $enseignant->setRoleUtil('enseignant'); //affectation du role (enseignant)
            $em->flush(); //envoyer $enseignant à la BD
            $enseignant->setPseudoUtil($enseignant->GenrerPseudoUtilisateur()); //(2) 
            $em->flush(); //envoyer de nouveau $enseignant à la BD après la modification du pseudo
            $pseudo1 = $enseignant->getPseudoUtil();
            return $this->render('utilisateur/succes.html.twig', ["pseudo" => $pseudo1]);
        }

        return $this->renderForm('utilisateur/AjouterEnseignant.html.twig', ['form_ajout_enseignant' => $FromEnseignant]);
    }

    #[Route('/AjoutEleve', name: 'ajouter_eleve')]
    public function AjouterEleve(ManagerRegistry $doctrine, Request $request)
    {
        $eleve = new Utilisateur();
        $FromEleve = $this->createForm(AjouterEleveType::class, $eleve);
        $FromEleve->handleRequest($request); //réccupérer le formulaire envoyé dans la requête

        if ($FromEleve->isSubmitted() && $FromEleve->isValid()) //si le formulaire est soumis
        {
            //dd($FromEnseignant->getData());
            $em = $doctrine->getManager();
            $em->persist($eleve);
            $eleve->setRoleUtil('élève'); //affectation du role (eleve)
            $em->flush(); //envoyer $eleve à la BD
            $eleve->setPseudoUtil($eleve->GenrerPseudoUtilisateur()); //(2) 
            $em->flush(); //envoyer de nouveau $enseignant à la BD après la modification du pseudo
            $pseudo2 = $eleve->getPseudoUtil();
            return $this->render('utilisateur/succes.html.twig', ["pseudo" => $pseudo2]);
        }
        return $this->renderForm('utilisateur/AjouterEleve.html.twig', ['form_ajout_eleve' => $FromEleve]);
    }

    //(2)affectation du pseudo généré après avoir fait le flush() parce que maintenant l'id est généré auto dans la BD






    // **************************** FIN Ajout Front Office *********************************



    //NB: ********************* fonction de test ****************
    #[Route('/routetest', name: 'route_test')]
    public function fonction_de_test(UtilisateurRepository $utilisateurRepository)
    {
        $ListAdmin = $utilisateurRepository->findAll();
        return $this->render('utilisateur/test.html.twig', array("liste_administrateurs" => $ListAdmin));
    }
}