<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Entity\Meet;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class TechController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager){

    }

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

    //Route pour exporter les adhérents
    #[
        Route('/tech/export', name: 'tech_export'),
        IsGranted('ROLE_TECH')
    ]
    public function techExportCsv(Request $request): Response
    {

        $adherentcsv = $this->entityManager->getRepository(Adherent::class)->findAll();
//        dd($adherentcsv);
//        $userAgences = $this->getUser()->getAgence();
//
//        foreach ($userAgences as $userAgence){
//            $adherents = $userAgence->getAdherents();
//            foreach ($adherents as $adherent){
//                if($adherent->getGenre()->getName() === 'Féminin'){
//                    $meets[] = $this->entityManager->getRepository(Meet::class)->findBy(['adherent_woman' => $adherent->getId()]);
//                } else {
//                    $meets[] = $this->entityManager->getRepository(Meet::class)->findBy(['adherent_man' => $adherent->getId()]);
//                }
//                $adherentcsv[] = $adherent;
//            }
//        }

        $csv = $this->renderView('tech/file/templateTech.csv.twig', [
            'adherents' => $adherentcsv,
        ]);

        $response = new Response($csv);

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            sprintf('file_%s.csv', time()));
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', $disposition);

        return $response;

    }

}
