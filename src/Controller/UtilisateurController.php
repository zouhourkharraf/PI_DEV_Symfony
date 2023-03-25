<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\AjouterEleveType;
use App\Form\AjouterUtilisateurType;
use App\Form\ModifierAdminType;
use App\Form\ModifierEleveType;
use App\Form\ModifierEnseignantType;
use App\Form\ReinitialiserMPType;
use App\Form\VerifCodeType;
use App\Form\VerifierPseudoType;
use App\Repository\UtilisateurRepository;
use App\Service\MailerService;
use Doctrine\Persistence\ManagerRegistry;
use PharIo\Manifest\Requirement;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

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
        $ListAdmin = $utilisateurRepository->findBy(array('role_util' => 'administrateur')); //récupérer tout les administrateurs
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
    /*
    #[Route('/routetest', name: 'route_test')]
    public function fonction_de_test(MailerService $mailer)
    {
        /*
        $detinataire = 'zouhour.kharraf1@esprit.tn';
        $objet = 'objet1';
        $contenu = '<p>AAAAAAAAAAAAAAAA</p><h1>AAAAAAAAAAAAAAAAAAAAAAAA</h1>';
        $mailer->sendEmail($detinataire, $objet, $contenu);
        */
    /*      return new Response('aaaaaaaaaa');
    }
*/

    // ***************** Méthodes pour l'utilisateur connecté ***************************

    //1) Liée à l'authentification : ******** La redirection: *****************
    // ----> cette méthode permet de rediriger l'utilisateur vers la page adéquate selon son rôle
    #[Route('/RouteRedirestion', name: 'route_redirection')]
    public function rediriger_utilisateur()
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


    // 3) Afficher profil enseignant:
    #[Route('/ProfilEnseignant/{id1}', name: 'profil_enseignant')]
    public function AfficherProfilEnseignant(UtilisateurRepository $repository, $id1)
    {
        $enseignant1 = $repository->findOneByid($id1);
        return $this->render('utilisateur/ProfilEnseignant.html.twig', ['enseignant' => $enseignant1]);
    }
    // ***************** Fin Méthodes pour l'utilisateur connecté ***************************






    //  ************************** réccupération du mot de passe: *****************************
    //1)verifier le pseudo 
    #[Route('/MotDePasseOublie', name: 'mot_de_passe_oublie')]
    public function gerer_lien_mp_oublie(UtilisateurRepository $repository, Request $request): Response
    {
        $FromVerifPseudo = $this->createForm(VerifierPseudoType::class);
        $FromVerifPseudo->handleRequest($request); //réccupérer le formulaire envoyé dans la requête
        $erreur = '';
        if ($FromVerifPseudo->isSubmitted()) {
            $pseudo_saisi = $FromVerifPseudo->getData()['pseudo']; //récupérer le pseudo saisi 
            $utilisateur = $repository->findOneByPseudoUtilisateur($pseudo_saisi); //retourner l'utilisateur avec le pseudo $pseudo_saisi
            if ($utilisateur == null) //si l'utilisateur n'existe pas
            {
                $erreur = "Le pseudo n'existe pas !";
            } else {
                $code_secret = rand(10000, 99999);
                $id = $utilisateur->getId(); //réccupérer l'email de l'utilisateur
                return $this->redirectToRoute('verifier_code', ['id' => $id, 'code' => $code_secret]);
            }
        }
        return $this->renderForm('utilisateur/VerifPseudo.html.twig', ['form_verif_pseudo' => $FromVerifPseudo, 'erreur_p' => $erreur]);
    }

    // 2) vérifier le code envoyé:
    #[Route('/verifiercode/{id}/{code}', name: 'verifier_code')]
    public function verifier_code(UtilisateurRepository $repository, Request $request, MailerService $mailer, $id, $code): Response
    {
        $utilisateur = $repository->findOneByid($id);
        $email_utilisateur = $utilisateur->getEmailUtil();
        //Envoyer un email à l'utilisateur qui contient un code secret
        $objet = 'Magic Book : Récupération du mot de passe';
        $contenu = " <h5>Bonjour " . $utilisateur->getPrenomUtil() . "</h5><h5>Votre code est : " . $code . "</h5>
        <p>
        <h5>
          L'équipe Magic Book
        </h5>
        </p>";
        $mailer->sendEmail($email_utilisateur, $objet, $contenu); //envoyer l'email
        $FromVerifCode = $this->createForm(VerifCodeType::class);
        $FromVerifCode->handleRequest($request);
        $erreur_code = '';
        if ($FromVerifCode->isSubmitted()) {
            if (strval($code) == $FromVerifCode->getData()['code_secret']) {
                return $this->redirectToRoute('modifier_MP', ['id3' => $id]);
            } else {
                $erreur_code = 'Code incorrect';
            }
            //  return new Response(dd($code));
        }

        //rediriger l'utilisateur vers une page pour saisir le code envoyé
        return $this->renderForm('utilisateur/VerifCode.html.twig', ['form_verif_code' => $FromVerifCode, 'erreur_c' => $erreur_code, 'email' => $email_utilisateur]);
    }

    //3) modifier le mot de passe en cas de succès
    #[Route('/modifierMP/{id3}', name: 'modifier_MP')]
    public function reinitialiser_mot_de_passe_utilisateur(UtilisateurRepository $repository, Request $request, $id3, ManagerRegistry $doctrine, UserPasswordHasherInterface $hacher): Response
    {
        $utilisateur = $repository->findOneByid($id3);
        $FromModif_MP = $this->createForm(ReinitialiserMPType::class);
        $FromModif_MP->handleRequest($request); //réccupérer le formulaire envoyé dans la requête 

        if ($FromModif_MP->isSubmitted() && $FromModif_MP->isValid()) {
            $em = $doctrine->getManager();
            $mp_hache = $hacher->hashPassword($utilisateur, $FromModif_MP->getData()['mot_de_passe']);
            $utilisateur->setMotDePasseUtil($mp_hache);
            // $em->persist($utilisateur);
            $em->flush();

            return $this->render('utilisateur/succes_update_password.html.twig');
        }

        return $this->renderForm('utilisateur/reinitialiserMP.html.twig', ['form_modif_mp' => $FromModif_MP]);
    }

    //  ************************** FIN réccupération du mot de passe: *****************************












    // ****************************************** Prasing JSON *********************************************************************

    // ****************** Affichage JSON ***********************************


    #[Route('/listUtilisateursJSON', name: 'liste_utilisateursJSON')]
    public function AfficherUtilisateurssJSON(SerializerInterface $serializer, UtilisateurRepository $utilisateurRepository)
    {
        $ListUsers = $utilisateurRepository->findAll();
        $json = $serializer->serialize($ListUsers, 'json', ['groups' => "utilisateurs"]);
        return new Response($json);
    }

    #[Route('/AfficherUtilisateur/{id1}', name: 'profil_utilisateur')]
    public function AfficherUtilisateur(NormalizerInterface $normalizer, UtilisateurRepository $repository, $id1)
    {
        $utilisateur = $repository->findOneByid($id1);
        $UtilisateurNormalises = $normalizer->normalize($utilisateur, 'json', ['groups' => "utilisateurs"]);
        return new Response(json_encode($UtilisateurNormalises));
    }

    // ****************** FIN Affichage JSON ***********************************

    // ****************** Ajout JSON ***********************************
    #[Route('/AjoutEnseignantJSON', name: 'ajouter_enseignantJSON')]
    public function AjouterEnseignantJSON(NormalizerInterface $normalizer, ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $hacher)
    {
        $enseignant = new Utilisateur(); //création de l'objet $enseignant de type Utilisateur


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

        $jsonContent = $normalizer->normalize($enseignant, 'json', ['groups' => 'utilisateurs']);
        return new Response(json_encode($jsonContent));
    }

    #[Route('/AjoutEleveJSON', name: 'ajouter_eleveJSON')]
    public function AjouterEleveJSON(NormalizerInterface $normalizer, ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $hacher)
    {
        $eleve = new Utilisateur();
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
        $jsonContent = $normalizer->normalize($eleve, 'json', ['groups' => 'utilisateurs']);
        return new Response(json_encode($jsonContent));
    }

    // ****************** FIN Ajout JSON ***********************************

    // ***************************************** Modification JSON *******************************************

    // 1) Modifier un enseignnat JSON
    #[Route('/ModifierEnseignantJSON/{id1}', name: 'modifier_enseignantJSON')]
    public function ModifierEnseignantJSON(UtilisateurRepository $repository, ManagerRegistry $doctrine, Request $request, $id1, NormalizerInterface $Normalizer)
    {
        $enseignant = $repository->findOneByid($id1);
        $em = $doctrine->getManager();
        $em->persist($enseignant);
        $em->flush(); //envoyer $enseignant à la BD
        $enseignant->setPseudoUtil($enseignant->GenrerPseudoUtilisateur()); //(2) 
        $em->flush(); //envoyer de nouveau $enseignant à la BD après la modification du pseudo

        $jsonContent = $Normalizer->normalize($enseignant, 'json', ['groups' => 'utilisateurs']);
        return new Response("Student updated successfully " . json_encode($jsonContent));
    }

    // 2) Modifier un eleve JSON
    #[Route('/ModifierEleveJSON/{id1}', name: 'modifier_eleveJSON')]
    public function ModifierEleveJSON(UtilisateurRepository $repository, ManagerRegistry $doctrine, Request $request, $id1, NormalizerInterface $Normalizer)
    {
        $eleve = $repository->findOneByid($id1);
        $em = $doctrine->getManager();
        $em->persist($eleve);
        $em->flush(); //envoyer $enseignant à la BD
        $eleve->setPseudoUtil($eleve->GenrerPseudoUtilisateur()); //(2) 
        $em->flush(); //envoyer de nouveau $enseignant à la BD après la modification du pseudo

        $jsonContent = $Normalizer->normalize($eleve, 'json', ['groups' => 'utilisateurs']);
        return new Response("Student updated successfully " . json_encode($jsonContent));
    }

    // ********************************************* FIN Modification JSON *******************************************

    //****************** Suppression JSON
    #[Route('/SupprimerUtilJSON/{id1}', name: 'supprimer_utilisateurJSON')]
    public function SupprimerUtilisateurJSON($id1, ManagerRegistry $doctrine, UtilisateurRepository $repository, NormalizerInterface $Normalizer)
    {
        $utilisateur = $repository->findOneByid($id1);
        $em = $doctrine->getManager();
        $em->remove($utilisateur);
        $em->flush();
        $jsonContent = $Normalizer->normalize($utilisateur, 'json', ['groups' => 'utilisateurs']);
        return new Response("Student deleted successfully " . json_encode($jsonContent));
    }

    //****************** FIN Suppression JSON

    // ************************************************** FIN prasing JSON *********************************************************

}
