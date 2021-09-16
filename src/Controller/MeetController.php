<?php

namespace App\Controller;

use App\Entity\Adherent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MeetController extends AbstractController
{
    //Page pour voir tous les rencontres
    #[Route('/meet', name: 'meet')]
    public function index(): Response
    {
        return $this->render('meet/index.html.twig', [
            'controller_name' => 'MeetController',
        ]);
    }

    //Page pour créer une rencontre
    #[
        Route('/meet/new/{woman}-{man}', name: 'meet_new'),
        Entity('adherent', expr:'repository.find(adherent.woman'),
        Entity('adherent', expr:'repository.find(adherent.man')
    ]
    public function newMeet(Adherent $woman, Adherent $man): Response
    {

        return $this->render('meet/newMeet.html.twig', [
            'woman' => $woman,
            'man' => $man
        ]);
    }
}
