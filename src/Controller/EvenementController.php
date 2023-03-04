<?php

namespace App\Controller;
use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
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

    #[Route("/AllEvents", name: "list")]
    //* Dans cette fonction, nous utilisons les services NormlizeInterface et StudentRepository,
        //* avec la méthode d'injection de dépendances.
    public function getEvents(EvenementRepository $repo, SerializerInterface $serializer)
    {
        $evenements = $repo->findAll();
        //* Nous utilisons la fonction normalize qui transforme le tableau d'objets
        //* students en  tableau associatif simple.
        // $studentsNormalises = $normalizer->normalize($students, 'json', ['groups' => "students"]);

        // //* Nous utilisons la fonction json_encode pour transformer un tableau associatif en format JSON
        // $json = json_encode($studentsNormalises);

        $json = $serializer->serialize($evenements, 'json', ['groups' => "evenements"]);

        //* Nous renvoyons une réponse Http qui prend en paramètre un tableau en format JSON
        return new Response($json);
    }

    #[Route("/Event/{id}", name: "event")]
    public function EventId($id, NormalizerInterface $normalizer, EvenementRepository $repo)
    {
        $evenement = $repo->find($id);
        $evenementNormalises = $normalizer->normalize($evenement, 'json', ['groups' => "evenements"]);
        return new Response(json_encode($evenementNormalises));
    }


    #[Route("addEventJSON/new", name: "addEventJSON")]
    public function addEventJSON(Request $req,NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $evenement = new Evenement();
        $evenement->setNomEv($req->get('nom_ev'));
        $evenement->setDatedEv($req->get('dated_ev'));
        $evenement->setDatefEv($req->get('datef_ev'));
        $evenement->setLieuEv($req->get('lieu_ev'));
        $evenement->setDescEv($req->get('desc_ev'));
        $evenement->setImageEv($req->get('image_ev'));
        $em->persist($evenement);
        $em->flush();

        $jsonContent = $Normalizer->normalize($evenement, 'json', ['groups' => 'evenements']);
        return new Response(json_encode($jsonContent));
    }
    #[Route('updateEventJSON/{id}', name: "updateEventJSON")]
    public function updateEventJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $evenement = $em->getRepository(Evenement::class)->find($id);
        $evenement->setNomEv($req->get('nom_ev'));
        $evenement->setDatedEv($req->get('dated_ev'));
        $evenement->setDatefEv($req->get('datef_ev'));
        $evenement->setLieuEv($req->get('lieu_ev'));
        $evenement->setDescEv($req->get('desc_ev'));
        $evenement->setImageEv($req->get('image_ev'));

        $em->flush();

        $jsonContent = $Normalizer->normalize($evenement, 'json', ['groups' => 'evenements']);
        return new Response("Event updated successfully " . json_encode($jsonContent));
    }

    #[Route("deleteEventJSON/{id}", name: "deleteEventJSON")]
    public function deleteStudentJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $evenement = $em->getRepository(Evenement::class)->find($id);
        $em->remove($evenement);
        $em->flush();
        $jsonContent = $Normalizer->normalize($evenement, 'json', ['groups' => 'students']);
        return new Response("Event deleted successfully " . json_encode($jsonContent));
    }

    #[Route('/star/{id}', name: 'star')]
    public function yourAction(Request $request,$id,ManagerRegistry $doctrine)
    {
        if ($request->isXmlHttpRequest()) {
            // handle the AJAX request
            $data = $request->getContent(); // retrieve the data sent by the client-side JavaScript code
            $repository = $doctrine->getRepository(Evenement::class);
            $evenements = $repository->find($id);
            if($evenements->getNoteEv()==0)
              $evenements->setNoteEv(5);
            else
                $evenements->setNoteEv(($evenements->getNoteEv()+$data[6])/2);//modifier la note du produit
            $em=$doctrine->getManager();
            $em->persist($evenements);
            $em->flush();
            $event = $repository->find($id);
            $test=$event->getNoteEv();
            $response = new Response($data[6]);//nouvelle instance du response pour la renvoyer a la fonction ajax
            $response->setContent(json_encode($test));//encoder les donnes sous forme JSON et les attribuer a la variable response
            $response->headers->set('Content-Type', 'application/json');
            return $response;//envoie du response
        }
    }


    #[Route('/get/{id}', name: 'getid')]
    public function show_id(ManagerRegistry $doctrine, $id): Response
    {
        $repository = $doctrine->getRepository(Evenement::class);
        $produits = $repository->find($id);
        return $this->render('evenement/detail.html.twig', [
            'event' => $produits,
            'id' => $id,
        ]);
        return $this->redirectToRoute('app_evenement');
    }

    #[Route("/event/search", name: "event_search")]

    public function search(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('lieu', TextType::class)
            ->getForm();

        $events = [];
        if ($request->isMethod('POST')) {
            $lieu = $request->request->get('form')['lieu'];
            $events = $this->getDoctrine()
                ->getRepository(Evenement::class)
                ->findByLocation($lieu);
        }

        return $this->render('evenement/search.html.twig', [
            'form' => $form->createView(),
            'events' => $events,
        ]);
    }




}
