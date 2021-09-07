<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Entity\AdherentOption;
use App\Form\AdherentType;
use App\Form\OptionType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[
        Route('/', name: 'home'),
        IsGranted('ROLE_ADMIN')
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

    #[Route('/option', name: 'option')]
    public function addOption(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $option = new AdherentOption();

        $form = $this->createForm(OptionType::class, $option);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $option = $form->getData();

            $entityManagerInterface->persist($option);
            $entityManagerInterface->flush();
        }

        return $this->render('home/option.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
