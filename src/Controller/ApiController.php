<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Entity\Meet;
use App\Repository\AdherentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    #[
        Route('/api/adherent/{id}', name: 'api_adherent', methods: 'GET')
    ]
    public function index(Adherent $adherent ,SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        $adherentApi = $entityManager->getRepository(Adherent::class)->findBy(['id' => $adherent->getId()]);

        $adherentApiGenre = $adherentApi[0]->getGenre()->getName();

        if ($adherentApiGenre === 'Féminin'){
            $adherentMeetApi = $entityManager->getRepository(Meet::class)->findBy(['adherent_woman' => $adherentApi[0]->getId()]);
        } else {
            $adherentMeetApi = $entityManager->getRepository(Meet::class)->findBy(['adherent_man' => $adherentApi[0]->getId()]);
        }

        //Gestion de l'api
        $adherentApi = [
            'adherent' => $adherentApi,
            'meet' => $adherentMeetApi
        ];

        return $this->json($adherentApi, 200, [], ['groups' => 'adherent:read']);

    }

    #[Route('/api/meet/{id}', name: 'api_meet', methods: 'GET')]
    public function meet(Meet $meet ,SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        $meetApi = $entityManager->getRepository(Meet::class)->findBy(['id' => $meet->getId()]);

//        dd($meetApi);
        //Gestion de l'api


        $response = $this->json($meetApi, 200, [], ['groups' => 'meet:read']);
//        dd($response);
        return $response;

    }
}
