<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdventureController extends AbstractController
{
    #[Route('/', name: 'app_adventure')]
    public function index(): Response
    {
        return $this->render('adventure/index.html.twig', [
            'controller_name' => 'AdventureController',
        ]);
    }
}
