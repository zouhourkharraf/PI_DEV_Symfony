<?php

namespace App\Controller;



use App\Entity\Cours; 
use Symfony\Component\Mime\Email;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; 
use App\Form\CoursType;
use App\Form\FileType;
use App\Repository\CoursRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;


class CoursController extends AbstractController
{
    #[Route('/cours', name: 'app_cours')]
    public function index(): Response
    {
        return $this->render('cours/index.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }
     
    #[Route('/Cours/add', name: 'cour_add')]
    public function addCour(ManagerRegistry $doctrine,Request $req,SluggerInterface $slugger,Request $request): Response {

        $cours = new Cours();
        $form = $this->createForm(CoursType::class,$cours);
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
                        $this->getParameter('Cours_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $cours->setFichier($newFilename);
            }

            $em = $doctrine->getManager();
            $em->persist($cours);
            $em->flush();
            
            return $this->redirectToRoute('app_aff');
        }

        return $this->renderForm('cours/Ajoutercour.html.twig',['form'=>$form]);
    } 
    #[Route('/Cours/list/{id}', name: 'cour_list')]
    public function listcourens(CoursRepository $coursRepository,$id,Request $request): Response
    {
        
        $cours = $coursRepository->findByMatiere($id);
        return $this->render("cours/affichagecour.html.twig",array("tabcours"=>$cours));
    }


    #[Route('/removeCour/{id}', name: 'app_removeCour')]

    public function deleteEvent(ManagerRegistry $doctrine,$id,CoursRepository $repository,Request $request): Response
    {
        $cours= $repository->find($id);
        $em= $doctrine->getManager();
        $em->remove($cours);
        $em->flush();
        return $this->redirectToRoute('app_aff');
        
    }

    #[Route('/updateCour/{id}', name: 'app_updateCour')]
    public function updateEvent(CoursRepository $repository,$id,ManagerRegistry $doctrine,Request $request,SluggerInterface $slugger)
    {
        $cours= $repository->find($id);
        $form=$this->createForm(CoursType::class,$cours);
        $form->handleRequest($request);
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
                        $this->getParameter('Cours_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $cours->setFichier($newFilename);
            }


            $em =$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_aff');
        }
        return $this->renderForm('cours/Ajoutercour.html.twig',['form'=>$form]);

    }
    #[Route('/Cours/listeleve', name: 'cour_listele')]
    public function listcoureleve(CoursRepository $repository): Response
    {
        $cours= $repository->findAll();
        return $this->render("cours/AffEleve.html.twig",array("tabcours"=>$cours));
    }
    
    #[Route('/mobile/Cours/list', name: 'mobile_cour_list')]
    function les_cours(CoursRepository $repository, NormalizerInterface $normalizer): JsonResponse
   {
       $cours = $repository->findAll();
       $jsonContent = $normalizer->normalize($cours);
   
       return new JsonResponse($jsonContent, JsonResponse::HTTP_OK);
   } 
   
/////////////////////BACK//////////////////////////////:

#[Route('/Coursback/{id}', name: 'cour_listback')]
    public function listcourensback(CoursRepository $repository,$id): Response
    {
        $cours = $repository->findByMatiere($id);
        return $this->render("cours/affichBackCour.html.twig",array("tabcoursback"=>$cours));
    }
    #[Route('/Cours/addback', name: 'cour_addback')]
    public function addCourback(Request $request,ManagerRegistry $doctrine,Request $req,SluggerInterface $slugger): Response {

        $cours = new Cours();
        $form = $this->createForm(CoursType::class,$cours);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){

            $photo = $form->get('photo')->getData();

            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                try {
                    $photo->move(
                        $this->getParameter('Cours_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $cours->setFichier($newFilename);
            }

            $em = $doctrine->getManager();
            $em->persist($cours);
            $em->flush();
            return $this->redirectToRoute('app_affback');
        }

        return $this->renderForm('cours/AjoutBackCour.html.twig',['form'=>$form]);
    } 
    #[Route('/removeCourback/{id}', name: 'app_removeCourback')]
    public function deleteEventback(Request $request,ManagerRegistry $doctrine,$id,CoursRepository $repository, MailerInterface $mailer): Response
    {
        $cours= $repository->find($id);
        $em= $doctrine->getManager();
        $em->remove($cours);
        $em->flush();
       

        return $this->redirectToRoute('app_affback');
    }
    
    #[Route('/updateCourback/{id}', name: 'app_updateCourback')]
    public function updateEventback(CoursRepository $repository,$id,ManagerRegistry $doctrine,Request $request,SluggerInterface $slugger)
    {
        $cours= $repository->find($id);
        $form=$this->createForm(CoursType::class,$cours);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $photo = $form->get('photo')->getData();
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                try {
                    $photo->move(
                        $this->getParameter('Cours_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $cours->setFichier($newFilename);
            }


            $em =$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_affback');
        }
        return $this->renderForm('cours/AjoutBackCour.html.twig',['form'=>$form]);

    }

}
