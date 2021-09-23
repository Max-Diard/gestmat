<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Entity\AdherentOption;
use App\Entity\Meet;
use App\Repository\AdherentRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager){

    }
    #[
        Route('/api/adherent/{id}', name: 'api_adherent', methods: 'GET'),
        IsGranted('ROLE_USER')
    ]
    public function index(Adherent $adherent ,SerializerInterface $serializer): Response
    {
        $adherentApi = $this->entityManager->getRepository(Adherent::class)->findBy(['id' => $adherent->getId()]);

        $adherentApiGenre = $adherentApi[0]->getGenre()->getName();

        if ($adherentApiGenre === 'Féminin'){
            $adherentMeetApi = $this->entityManager->getRepository(Meet::class)->findBy(['adherent_woman' => $adherentApi[0]->getId()]);
        } else {
            $adherentMeetApi = $this->entityManager->getRepository(Meet::class)->findBy(['adherent_man' => $adherentApi[0]->getId()]);
        }

        //Gestion de l'api
        $adherentApi = [
            'adherent' => $adherentApi,
            'meet' => $adherentMeetApi
        ];

        return $this->json($adherentApi, 200, [], ['groups' => 'adherent:read']);

    }

    #[Route('/api/meet/{id}', name: 'api_meet', methods: 'GET')]
    public function meet(Meet $meet ,SerializerInterface $serializer): Response
    {
        $meetApi = $this->entityManager->getRepository(Meet::class)->findBy(['id' => $meet->getId()]);

//        dd($meetApi);
        //Gestion de l'api


        $response = $this->json($meetApi, 200, [], ['groups' => 'meet:read']);
//        dd($response);
        return $response;

    }

    #[Route('/api/update_meet/', name: 'api_update_meet', methods: 'POST')]
    public function infoMeet(Request $request): Response
    {
//        dd($request->getContent());
        $data = json_decode(
            $request->getContent(),
            true
        );

        $meet = $this->entityManager->getRepository(Meet::class)->findBy(['id' => $data['id']]);

        if($data['status_meet_woman']){
            $options = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'status_meet']);
            foreach ($options as $option){
                if ($option->getName() == $data['status_meet_woman']){
                    $meet[0]->setStatusMeetWoman($option);
                }
            }
        }
        if($data['status_meet_man']){
            $options = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'status_meet']);
            foreach ($options as $option){
                if ($option->getName() == $data['status_meet_woman']){
                    $meet[0]->setStatusMeetMan($option);
                }
            }
        }
        if($data['returnAt_woman']){
            $date = new DateTimeImmutable($data['returnAt_woman']);
            $meet[0]->setReturnAtWoman($date);
        }
        if($data['returnAt_man']){
            $date = new DateTimeImmutable($data['returnAt_man']);
            $meet[0]->setReturnAtMan($date);
        }
        if($data['comments_woman']){
            $meet[0]->setCommentsWoman($data['comments_woman']);
        }
        if($data['comments_man']){
            $meet[0]->setCommentsMan($data['comments_man']);
        }

        $this->entityManager->persist($meet[0]);
        $this->entityManager->flush();

        return new JsonResponse(
            [
                'status' => 'ok',
            ],
            JsonResponse::HTTP_CREATED
        );
    }
}
