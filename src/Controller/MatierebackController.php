<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Form\MatiereType;
use Doctrine\DBAL\Schema\View;
use App\Form\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\MatiereRepository;
use App\Repository\CoursRepository;
use Doctrine\Persistence\ManagerRegistry;
use SebastianBergmann\CodeCoverage\Report\Html\Renderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MatierebackController extends AbstractController
{
    #[Route('/matiereback', name: 'app_matiereback')]
    public function index(): Response
    {
        return $this->render('matiereback/index.html.twig', [
            'controller_name' => 'MatierebackController',
        ]);
    }
    ////////afficher/////////////
    #[Route('/AfficherMatiereback', name: 'app_affback')]
    public function liste(ManagerRegistry $mg): Response
    {
        $repo = $mg->getRepository(Matiere::class);
        $resultat = $repo->FindAll();
        return $this->render('matiere/backaffichage.html.twig', [
            'matiere' => $resultat,
        ]);
    }
    #[Route('/excel', name: 'excel')]
    public function generateExcel(MatiereRepository $matiereRepository, CoursRepository $coursRepository): Response
    {
        try {
            session_start();
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $row = 1;

            $sheet->setCellValue('A' . $row, 'ID Matiere');
            $sheet->setCellValue('B' . $row, 'Nom de la Matière');
            $matieres = $matiereRepository->findAll();
            $row++;

            foreach ($matieres as $matiere) {
                $sheet->setCellValue('A' . $row, $matiere->getId());
                $sheet->setCellValue('B' . $row, $matiere->getNomMat());
                $cours = $coursRepository->findByMatiere($matiere->getId());
                $sheet->setCellValue('C' . $row, 'ID Cours');
                $sheet->setCellValue('D' . $row, 'Titre du Cours');
                $row++;

                foreach ($cours as $c) {
                    $sheet->setCellValue('C' . $row, $c->getId());
                    $sheet->setCellValue('D' . $row, $c->getTitreCour());
                    $row++;
                }
            }

            $writer = new Xlsx($spreadsheet);
            $filename = 'matieres_cours.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            $writer->save('php://output');

            return new Response();

        } catch (\Exception $e) {
            return new Response('Error: Unable to generate Excel file' . $e, 500);
        }
    }

    #[Route('/RemoveMatiereback/{id}', name: 'app_removeback')]
    public function remove(ManagerRegistry $mg, MatiereRepository $X, $id): Response
    {

        $Categorie = $X->find($id);
        $em = $mg->getManager();
        $em->remove($Categorie);
        $em->flush();
        return $this->redirectToRoute('app_affback');
    }

    #[Route('/AjouterMatiereback', name: 'AjouterMatiereback')]
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
                ->subject('Matiere Ajouter')
                ->html($this->renderView('email/matiere_added_email.html.twig', ['matiere' => $Categorie]));


            $mailer->send($email);

            return $this->redirectToRoute("app_affback");
        }

        return $this->renderForm("matiere/AjouterbackM.html.twig", array("AjouterMatiere" => $form));
    }
    #[Route('/UpdateMatiereback/{id}', name: 'app_updateback')]
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
                ->html($this->renderView('email/matiere_added_email.html.twig', ['matiere' => $Categorie]));


            $mailer->send($email);

            return $this->redirectToRoute('app_affback');
        }
        return $this->renderForm("matiere/AjouterbackM.html.twig", array("AjouterMatiere" => $form));

    }

    #[Route('/matiere/search', name: 'matiere_search')]
    public function search(Request $request, MatiereRepository $matiereRepository)
    {
        $matiereId = $request->request->get('matiereId');

        // Perform the search based on $matiereId
        $matiere = $matiereRepository->find($matiereId);

        if ($matiere) {
            // Handle the case when matiere is found
            return $this->redirectToRoute('app_showMatiere', ['id' => $matiere->getId()]);
        } else {
            // Handle the case when matiere is not found
            $this->addFlash('error', 'Matiere not found');
            return $this->redirectToRoute('app_affback');
        }
    }
}