<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\AjouterEleveType;
use App\Form\AjouterUtilisateurType;
use App\Form\ModifierAdminType;
use App\Form\ModifierEleveType;
use App\Form\ModifierEnseignantType;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
    public function AfficherAdministrateurs(UtilisateurRepository $utilisateurRepository, UserPasswordHasherInterface $hacher, ManagerRegistry $doctrine)
    {
        $ListAdmin = $utilisateurRepository->findBy(array('role_util' => 'administrateur')); //récupérer tout les administrateurs
        $em = $doctrine->getManager();
        //on va hacher les mots de passe des administrateurs déja enregistés avant de faire l'affichage
        foreach ($ListAdmin as $admin) {
            $mp_hache = $hacher->hashPassword($admin, $admin->getMotDePasseUtil());
            $admin->setMotDePasseUtil($mp_hache);
            $em->persist($admin);
        } // ------>fin du hachage des mots de passe
        $em->flush(); //envoyer tous les données à la BD
        $ListAdmin = $utilisateurRepository->findBy(array('role_util' => 'administrateur')); //récupérer tout les administrateurs après avoir modifier leurs mot de passe

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
    public function AjouterEnseignant(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $hacher)
    {
        $enseignant = new Utilisateur(); //création de l'objet $enseignant de type Utilisateur
        $FromEnseignant = $this->createForm(AjouterUtilisateurType::class, $enseignant); //création du formulaire associé à l'objet $enseignant
        $FromEnseignant->handleRequest($request); //réccupérer le formulaire envoyé dans la requête 

        if ($FromEnseignant->isSubmitted() && $FromEnseignant->isValid()) //si le formulaire est soumis
        {
            //dd($FromEnseignant->getData());
            $enseignant->setPseudoUtil(''); //initialiser le pseudo
            //hacher le mot de passe de l'utilisateur
            $mp_hache = $hacher->hashPassword($enseignant, $enseignant->getMotDePasseUtil());
            $enseignant->setMotDePasseUtil($mp_hache);


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
    public function AjouterEleve(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $hacher)
    {
        $eleve = new Utilisateur();
        $FromEleve = $this->createForm(AjouterEleveType::class, $eleve);
        $FromEleve->handleRequest($request); //réccupérer le formulaire envoyé dans la requête

        if ($FromEleve->isSubmitted() && $FromEleve->isValid()) //si le formulaire est soumis
        {
            //dd($FromEnseignant->getData());
            //hacher le mot de passe de l'utilisateur
            $eleve->setPseudoUtil(''); //initialiser le pseudo
            $mp_hache = $hacher->hashPassword($eleve, $eleve->getMotDePasseUtil());
            $eleve->setMotDePasseUtil($mp_hache);


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


    // **************************** Modifier *********************************

    // 1) Modifier un enseignnat
    #[Route('/ModifierEnseignant/{id1}', name: 'modifier_enseignant')]
    public function ModifierEnseignant(UtilisateurRepository $repository, ManagerRegistry $doctrine, Request $request, $id1)
    {
        $enseignant = $repository->findOneByid($id1);
        $FromEnseignant = $this->createForm(ModifierEnseignantType::class, $enseignant);
        $FromEnseignant->handleRequest($request); //réccupérer le formulaire envoyé dans la requête 

        if ($FromEnseignant->isSubmitted() && $FromEnseignant->isValid()) //si le formulaire est soumis et valide
        {
            $em = $doctrine->getManager();
            $em->persist($enseignant);
            $em->flush(); //envoyer $enseignant à la BD
            $enseignant->setPseudoUtil($enseignant->GenrerPseudoUtilisateur()); //(2) 
            $em->flush(); //envoyer de nouveau $enseignant à la BD après la modification du pseudo
            $pseudo1 = $enseignant->getPseudoUtil();
            return $this->render('utilisateur/succes_update.html.twig', ["pseudo" => $pseudo1]);
        }

        return $this->renderForm('utilisateur/ModifierEnseignant.html.twig', ['form_ajout_enseignant' => $FromEnseignant]);
    }
    // 2) Modifier un eleve
    #[Route('/ModifierEleve/{id1}', name: 'modifier_eleve')]
    public function ModifierEleve(UtilisateurRepository $repository, ManagerRegistry $doctrine, Request $request, $id1)
    {
        $eleve = $repository->findOneByid($id1);
        $FromEleve = $this->createForm(ModifierEleveType::class, $eleve);
        $FromEleve->handleRequest($request); //réccupérer le formulaire envoyé dans la requête 

        if ($FromEleve->isSubmitted() && $FromEleve->isValid()) //si le formulaire est soumis et valide
        {
            $em = $doctrine->getManager();
            $em->persist($eleve);
            $em->flush(); //envoyer $enseignant à la BD
            $eleve->setPseudoUtil($eleve->GenrerPseudoUtilisateur()); //(2) 
            $em->flush(); //envoyer de nouveau $enseignant à la BD après la modification du pseudo
            $pseudo1 = $eleve->getPseudoUtil();
            return $this->render('utilisateur/succes_update.html.twig', ["pseudo" => $pseudo1]);
        }

        return $this->renderForm('utilisateur/ModifierEleve.html.twig', ['form_ajout_enseignant' => $FromEleve]);
    }
    // ************ 3) Modifier le mot de passe d'un utilisateur ***********************



    // **************************** FIN Modifier *********************************

    // **************************** Supprimer *********************************

    #[Route('/SupprimerUtil/{id1}', name: 'supprimer_utilisateur')]
    public function SupprimerUtilisateur($id1, ManagerRegistry $doctrine, UtilisateurRepository $repository)
    {
        $utilisateur = $repository->findOneByid($id1);

        $em = $doctrine->getManager();
        $em->remove($utilisateur);
        $em->flush();
        return $this->redirectToRoute('liste_utilisateur');
    }
    // **************************** FIN Supprimer *********************************




    //NB: ********************* fonction de test ****************
    #[Route('/routetest', name: 'route_test')]
    public function fonction_de_test(UtilisateurRepository $utilisateurRepository)
    {
        /*$ListAdmin = $utilisateurRepository->findAll();
        return $this->render('utilisateur/test.html.twig', array("liste_administrateurs" => $ListAdmin));
        */
        return new Response(dd($this->getUser()));
    }

    // ***************** Méthodes pour l'utilisateur connecté ***************************

    //1)redirection:
    #[Route('/RouteRedirestion', name: 'route_redirection')] //cette méthode permet de rediriger l'utilisateur vers la page adéquate selon son rôle
    public function rediriger_utilisateur(UtilisateurRepository $utilisateurRepository)
    {
        if ($this->getUser()->getRoleUtil() == 'élève' || $this->getUser()->getRoleUtil() == 'enseignant') {
            return $this->redirectToRoute('page_utilisateur_connecte');
        } else {
            return $this->redirectToRoute('page_acceuil_back_office');
        }
    }
    // 2) Afficher profil élève:
    #[Route('/ProfilEleve/{id1}', name: 'profil_eleve')]
    public function AfficherProfilEleve(UtilisateurRepository $repository, $id1)
    {
        $eleve1 = $repository->findOneByid($id1);
        return $this->render('utilisateur/ProfilEleve.html.twig', ['eleve' => $eleve1]);
    }
    #[Route('/ProfilEnseignant/{id1}', name: 'profil_enseignant')]
    public function AfficherProfilEnseignant(UtilisateurRepository $repository, $id1)
    {
        $enseignant1 = $repository->findOneByid($id1);
        return $this->render('utilisateur/ProfilEnseignant.html.twig', ['enseignant' => $enseignant1]);
    }


    // 3) Afficher profil enseignant:

    // ***************** Fin Méthodes pour l'utilisateur connecté ***************************


}
