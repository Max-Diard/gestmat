<?php

namespace App\Controller;

use App\Entity\AdherentOption;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class AdminController extends AbstractController
{
   
    #[
        Route('/admin/option', name: 'option'),
        IsGranted('ROLE_ADMIN')
    ]
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