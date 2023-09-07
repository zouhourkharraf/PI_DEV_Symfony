<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Form\MatiereType;
use Doctrine\DBAL\Schema\View;
use App\Form\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\MatiereRepository;
use Doctrine\Persistence\ManagerRegistry;
use SebastianBergmann\CodeCoverage\Report\Html\Renderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;



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
    public function AjouterMatiere(ManagerRegistry $doctrine, Request $request, MailerInterface $mailer): Response
    {
        $Categorie = new Matiere();
        $form = $this->createForm(MatiereType::class, $Categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $doctrine->getManager();
            $em->persist($Categorie);
            $em->flush();
            $email = new Email();
            $email = (new Email())
                ->from('magicbook.pi@gmail.com')
                ->to('ellyssa.khalfaoui@esprit.tn')
                ->subject('Matiere Ajoutee')
                ->text("Matier Ajoutee : " . $Categorie->getNomMat());


            $mailer->send($email);
            return $this->redirectToRoute("app_aff");
        }
        return $this->renderForm("matiere/AjouterMatiere.html.twig", array("AjouterMatiere" => $form));

    }
    //////////////////////////afficher///////////////////////
    #[Route('/AfficherMatiére', name: 'app_aff')]
    public function liste(ManagerRegistry $mg): Response
    {
        $repo = $mg->getRepository(Matiere::class);
        $resultat = $repo->FindAll();
        return $this->render('matiere/afficher.html.twig', [
            'matiere' => $resultat,
        ]);
    }
    //////////////////////////modifier////////////////////////
    #[Route('/UpdateMatiere/{id}', name: 'app_update')]
    public function updateType(ManagerRegistry $doctrine, MatiereRepository $CategorieRepository, $id, Request $request, MailerInterface $mailer): Response
    {
        $Categorie = $CategorieRepository->find($id);
        $form = $this->CreateForm(MatiereType::class, $Categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $doctrine->getManager();
            $em->persist($Categorie);
            $em->flush();
            $email = new Email();
            $email = (new Email())
                ->from('magicbook.pi@gmail.com')
                ->to('ellyssa.khalfaoui@esprit.tn')
                ->subject('Matiere mise a jour')
                ->text("Matier mise a jour : " . $Categorie->getNomMat());


            $mailer->send($email);

            return $this->redirectToRoute('app_aff');
        }
        return $this->renderForm("matiere/AjouterMatiere.html.twig", array("AjouterMatiere" => $form));

    }

    //////////////////////////supprimer////////////////////////
    #[Route('/RemoveMatiere/{id}', name: 'app_remove')]
    public function remove(ManagerRegistry $mg, MatiereRepository $X, $id, MailerInterface $mailer): Response
    {

        $Categorie = $X->find($id);
        $em = $mg->getManager();
        $em->remove($Categorie);
        $em->flush();

        $email = new Email();
        $email = (new Email())
            ->from('magicbook.pi@gmail.com')
            ->to('ellyssa.khalfaoui@esprit.tn')
            ->subject('Matiere Supprimer')
            ->text("Matier Supprimer : " . $Categorie->getNomMat());


        $mailer->send($email);

        return $this->redirectToRoute('app_aff');
    }

    #[Route('/AfficherElevee', name: 'app_affl')]
    public function listeeleve(ManagerRegistry $mg): Response
    {
        $repo = $mg->getRepository(Matiere::class);
        $resultat = $repo->FindAll();
        return $this->render('matiere/AffichageEleve.html.twig', [
            'matiere' => $resultat,
        ]);
    }

}