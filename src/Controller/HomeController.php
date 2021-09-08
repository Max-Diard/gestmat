<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Form\AdherentType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[
        Route('/home', name: 'home'),
        IsGranted('ROLE_USER')
    ]
    public function index(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {

        return $this->render('home/index.html.twig', [
        ]);
    }
}
