<?php

namespace App\Controller;
use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;


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
    public function listevenement(Request $request,EvenementRepository $repository,PaginatorInterface $paginator): Response
    {
        $evenements= $repository->findAll();
        $evenements = $paginator->paginate(
            $evenements, /* query NOT result */
            $request->query->getInt('page', 1),
            4
        );
        return $this->render("evenement/listevenements.html.twig",array("tabEvenements"=>$evenements));
    }

    #[Route('/updateEvent/{id}', name: 'app_updateEvent')]
    public function updateEvent(EvenementRepository $repository,$id,ManagerRegistry $doctrine,Request $request,SluggerInterface $slugger)
    {
        $evenement= $repository->find($id);
        $form=$this->createForm(EvenementType::class,$evenement);
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

    #[Route('/evenementsF', name: 'app_evenementsF')]
    public function listevenementF(EvenementRepository $repository): Response
    {
        $evenements= $repository->findAll();
        return $this->render("evenement/listeventFront.html.twig",array("tabEvenements"=>$evenements));
    }

    #[Route('/traiter/{id}', name: 'participer')]
    function Traiter(EvenementRepository $repository, $id, Request $request, ManagerRegistry $doctrine)
    {

        $evenement = new Evenement();
        $evenement = $repository->find($id);
        // $reclamation->setEtat(1 );
        $em = $doctrine->getManager();
        $em->flush();
        $repository->sms();
        $this->addFlash('danger', 'reponse envoyée avec succées');
        return $this->redirectToRoute('app_evenement');

    }
    //Exporter pdf (composer require dompdf/dompdf)

     #[Route("/pdf", name :"PDF_Event", methods :["GET"])]
    public function pdf(EvenementRepository $repository)
     {
         // Configure Dompdf according to your needs
         $pdfOptions = new Options();
         $pdfOptions->set('defaultFont', 'Arial');

         // Instantiate Dompdf with our options
         $dompdf = new Dompdf($pdfOptions);
         // Retrieve the HTML generated in our twig file
         $html = $this->renderView('evenement/pdf.html.twig', [
             'tabEvents' => $repository->findAll(),
         ]);

         // Load HTML to Dompdf
         $dompdf->loadHtml($html);
         // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
         $dompdf->setPaper('A4', 'portrait');

         // Render the HTML as PDF
         $dompdf->render();
         // Output the generated PDF to Browser (inline view)
         $dompdf->stream("ListeDesEvenements.pdf", [
             "tabEvents" => true
         ]);
     }


}
