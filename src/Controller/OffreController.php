<?php
// src/Controller/OffreController.php
namespace App\Controller;

use App\Service\CallApiService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OffreController extends AbstractController
{
    /**
    * @Route("/")
    */
    public function listeOffres(CallApiService $callApiService): Response
    {
        
        return $this->render('offre/liste.html.twig', [
            'offres' => $callApiService->getListeOffres()
        ]);
        
    }
}