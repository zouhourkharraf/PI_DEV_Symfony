<?php

namespace App\Controller;

use App\Entity\Evenement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends AbstractController
{

    #[Route('/search', name: 'search')]
    public function index(Request $request): JsonResponse
    {
        $query = $request->query->get('q');
        $entityManager = $this->getDoctrine()->getManager();

        $events = $entityManager->getRepository(Evenement::class)
            ->createQueryBuilder('p')
            ->where('p.lieu_ev LIKE :query')
            ->setParameter('query', '%'.$query.'%')
            ->getQuery()
            ->getResult();

        if (empty($events)) {
            return $this->json(['message' => 'Event not found'], 404);
        }

        $responseData = [];
        foreach ($events as $event) {
            $responseData[] = [
                'id' => $event->getId(),
                'name' => $event->getNomEv(),
                'description' => $event->getDescEv(),
                'dated' => $event->getDatedEv(),
                'datef' => $event->getDatefEv(),
            ];
        }

        return $this->json($responseData);

    }



}
