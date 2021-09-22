<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Entity\Meet;
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
    #[Route('/meet', name: 'meet_all')]
    public function index(): Response
    {
        $meets = $this->entityManager->getRepository(Meet::class)->findAll();

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
