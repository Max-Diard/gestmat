<?php

namespace App\Controller;

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
        // $adhrent = new Adherent();

        // $form = $this->createForm(AdherentType::class, $adhrent);

        // $form->handleRequest($request);

        // if($form->isSubmitted() && $form->isValid()){
        //     $adherent = $form->getData();
            
        //     $entityManagerInterface->persist($adhrent);
        //     $entityManagerInterface->flush();
        // }

        return $this->render('home/index.html.twig', [
            // 'form' => $form->createView()
        ]);
    }
}
