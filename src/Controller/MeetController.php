<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Entity\Agence;
use App\Entity\Meet;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MeetController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    //Page pour voir tous les rencontres
    #[
        Route('/meet', name: 'meet_all')
    ]
    public function index(): Response
    {
        $agenceUser = $this->getUser()->getAgence();

        if(count($agenceUser) > 0){
            foreach($agenceUser as $agence){
                $adherents[] = $this->entityManager->getRepository(Adherent::class)->findBy(['agence' => $agence->getId()]);
                foreach ($adherents as $allAdherent){
                    foreach ($allAdherent as $adherent){
                        if ($adherent->getGenre()->getName() === 'Féminin'){
                            $meets[] = $this->entityManager->getRepository(Meet::class)->findBy(['adherent_woman' => $adherent->getId()]);
                        } else {
                            $meets[] = $this->entityManager->getRepository(Meet::class)->findBy(['adherent_man' => $adherent->getId()]);
                        }
                    }
                }
            }
        }
        $trueMeet = [];
        foreach ($meets as $meet){
            if (!empty($meet)){
                foreach($meet as $m){
                    $trueMeet += [
                        $m->getId() => $m
                    ];
                }
            }
        }
//        dd($trueMeet);

        return $this->render('meet/index.html.twig', [
            'meets' => $trueMeet
        ]);
    }

    //Page pour voir tous les rencontres
    #[
        Route('/meet/search/{date}', name: 'meet_date'),
        ParamConverter('date' , \DateTimeImmutable::class)
    ]
    public function searchMeet(DateTimeImmutable $date): Response
    {
//        dd($date);
        $meets = $this->entityManager->getRepository(Meet::class)->findBy(['startedAt' => $date]);

//        dd($meets);

        return $this->render('meet/index.html.twig', [
            'meets' => $meets
        ]);
    }

    //Route pour créer une rencontre
    #[
        Route('/meet/new/{woman}-{man}-{date}', name: 'meet_new'),
        Entity('adherent', expr:'repository.find(adherent.woman'),
        Entity('adherent', expr:'repository.find(adherent.man'),
        ParamConverter('date' , \DateTimeImmutable::class)
    ]
    public function newMeet(Adherent $woman, Adherent $man, DateTimeImmutable $date): Response
    {
        $meet = new Meet();

        $meet->setAdherentWoman($woman);
        $meet->setAdherentMan($man);
        $meet->setStartedAt($date);

        $this->entityManager->persist($meet);
        $this->entityManager->flush();


        return $this->redirectToRoute('adherent_all');
    }
}
