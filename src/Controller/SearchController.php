<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Entity\Agence;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[
        Route('/search', name: 'search'),
        IsGranted('ROLE_USER')
    ]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $agences = $this->getUser()->getAgence();

        if(count($agences) > 0){
            foreach($agences as $agence){
                $adherents[] = $entityManager->getRepository(Adherent::class)->findBy(['agence' => $agence->getId()]);

                $otherAgences = $entityManager->getRepository(Agence::class)->findOtherAgence($agence);

                if(count($otherAgences) > count($agences)){
                    foreach($otherAgences as $otherAgence){
                        $allAgence = $otherAgence->getDroitAgence();
                        if(count($allAgence) > 0){
                            $adherents[] = $entityManager->getRepository(Adherent::class)->findBy(['agence' => $otherAgence->getId()]);
                        }
                    }
                }
            }
        }

        return $this->render('search/index.html.twig', [
            'adherents' => $adherents
        ]);
    }
}
