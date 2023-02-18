<?php

namespace App\Controller;
use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class EvenementController extends AbstractController
{
    #[Route('/evenement', name: 'app_evenement')]
    public function index(): Response
    {
        return $this->render('evenement/index.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }
    #[Route('/Evenement/add', name: 'Evenement_add')]
    public function addEvent(ManagerRegistry $doctrine,Request $req,SluggerInterface $slugger): Response {

        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class,$evenement);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){

            $photo = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('evenement_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $evenement->setImageEv($newFilename);
            }

            $em = $doctrine->getManager();
            $em->persist($evenement);
            $em->flush();
            return $this->redirectToRoute('app_evenements');
        }

        return $this->renderForm('Evenement/add_ev.html.twig',['form'=>$form]);
    }

    #[Route('/evenements', name: 'app_evenements')]
    public function listevenement(EvenementRepository $repository): Response
    {
        $evenements= $repository->findAll();
        return $this->render("evenement/listevenements.html.twig",array("tabEvenements"=>$evenements));
    }

    #[Route('/updateEvent/{id}', name: 'app_updateEvent')]
    public function updateEvent(EvenementRepository $repository,$id,ManagerRegistry $doctrine,Request $request)
    {
        $evenement= $repository->find($id);
        $form=$this->createForm(EvenementType::class,$evenement);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em =$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute("app_evenements");
        }
        return $this->renderForm('Evenement/update_ev.html.twig',['form'=>$form]);

    }

    #[Route('/removeEvent/{id}', name: 'app_removeEvent')]

    public function deleteEvent(ManagerRegistry $doctrine,$id,EvenementRepository $repository)
    {
        $evenement= $repository->find($id);
        $em= $doctrine->getManager();
        $em->remove($evenement);
        $em->flush();
        return $this->redirectToRoute("app_evenements");

    }



}
