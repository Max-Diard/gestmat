<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TechController extends AbstractController
{
    #[
        Route('/tech', name: 'tech'),
        IsGranted('ROLE_TECH')
    ]
    public function index(): Response
    {
        return $this->render('tech/index.html.twig', [
            'controller_name' => 'TechController',
        ]);
    }
}
