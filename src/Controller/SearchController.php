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
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[
        Route('/search', name: 'search'),
        IsGranted('ROLE_USER')
    ]
    public function index(): Response
    {
        $agences = $this->getUser()->getAgence();

        if(count($agences) > 0){
            foreach($agences as $agence){
                $adherents[] = $this->entityManager->getRepository(Adherent::class)->findBy(['agence' => $agence->getId()]);

//                $otherAgences = $this->entityManager->getRepository(Agence::class)->findOtherAgence($agence);
//
//                if(count($otherAgences) > count($agences)){
//                    foreach($otherAgences as $otherAgence){
//                        $allAgence = $otherAgence->getDroitAgence();
//                        if(count($allAgence) > 0){
//                            $adherents[] = $this->entityManager->getRepository(Adherent::class)->findBy(['agence' => $otherAgence->getId()]);
//                        }
//                    }
//                }
            }
            $adherentInProgress = [];
            $date = new \DateTimeImmutable('now');
            if (!empty($adherents)){
                foreach ($adherents as $boucle){
                    foreach ($boucle as $adherent){
                        if ($adherent->getContractEndingAt() > $date){
                            $adherentInProgress[] = $adherent;
                        } else {
                            $adherentFinish[] = $adherent;
                        }
                    }
                }
                $boucleAdherentInProgress[] = $adherentInProgress;
            } else {
                $boucleAdherentInProgress = '';
            }

            return $this->render('search/index.html.twig', [
                'adherents' => $boucleAdherentInProgress
            ]);
        } else {
            $this->addFlash('noAgence', 'Vous n\'avez pas encore d\'agence associé à votre compte, merci de contacter l\'administrateur !');
            return $this->redirectToRoute('adherent_all');
        }
    }

    #[
        Route('/search-all-adherent', name: 'search-all-adherent'),
        IsGranted('ROLE_USER')
    ]
    public function searchAllAgence(EntityManagerInterface $entityManager): Response
    {
        $agence = $this->getUser()->getAgence();

        if(count($this->getUser()->getAgence()) > 0 ){
            $otherAgences = $this->entityManager->getRepository(Agence::class)->findOtherAgence($agence);

            foreach ($otherAgences as $otherAgence){
                $adherentOtherAgence[] = $this->entityManager->getRepository(Adherent::class)->findBy(['agence' => $otherAgence]);
            }

            return $this->render('search/searchInterAgence.html.twig', [
                'adherents' => $adherentOtherAgence
            ]);
        } else {
            $this->addFlash(
                'noAgence',
                'Vous n\'avez pas encore d\'agence associé à votre compte, merci de contacter l\'administrateur !'
            );
            return $this->redirectToRoute('adherent_all');
        }
    }
}
