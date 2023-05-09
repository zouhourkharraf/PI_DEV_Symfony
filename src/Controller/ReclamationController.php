<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\SearchType;
use Knp\Snappy\Pdf;
use Dompdf\Dompdf;

#[Route('/reclamation')]
class ReclamationController extends AbstractController
{

    #[Route('/pdf/{id}', name: 'export_reclamation_pdf', methods: ['GET'])]
    public function exportAction(Pdf $pdf, $id)
    {
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);
        $html = $this->renderView('reclamation/pdf.html.twig', [
            'reclamation' => $reclamation,
        ]);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();

        return new Response(
            $dompdf->stream('resume', ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }

    #[Route('/', name: 'app_front_reclamation_index', methods: ['GET'])]
    public function indexFront(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/index_front.html.twig', [
            'reclamation' => $reclamationRepository->findAll(),
        ]);
    }

    #[Route('/rec', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReclamationRepository $reclamationRepository): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationRepository->save($reclamation, true);
            $reclamationRepository->sms();
            return $this->redirectToRoute('page_utilisateur_connecte', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }
    #[Route('/his', name: 'app_reclamation_show1', methods: ['GET'])]
    public function show1(Reclamation $reclamation): Response
    {

        return $this->render('reclamation/historique.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationRepository->save($reclamation, true);

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reclamation->getId(), $request->request->get('_token'))) {
            $reclamationRepository->remove($reclamation, true);
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('search', name: 'reclamation_search')]

    public function search(ReclamationRepository $reclamationRepository, Request $request): Response
    {
        $issetTitre = isset($_GET['titre']);
        if ($issetTitre) {

            $reclamations = $reclamationRepository->searchReclamations($_GET['titre']);

            return $this->render('reclamation/index.html.twig', [
                'reclamations' => $reclamations,
            ]);
        }

        return $this->render('reclamation/search.html.twig', [
            'reclamationsearch' => $form->createView(),
        ]);
    }
}
