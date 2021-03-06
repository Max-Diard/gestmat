<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Entity\AdherentOption;
use App\Entity\Meet;
use App\Entity\User;
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

    //Api en get pour récupérer les adhérents femmes
    #[
        Route('/api/adherent/woman/{id}', name:'api_adherent_woman', methods: 'GET'),
        IsGranted('ROLE_USER')
    ]
    public function adherentWoman(User $user)
    {
        $user = $this->entityManager->getRepository(User::class)->findBy(['id' => $user]);
        dd($user[0]);

        $woman = $this->entityManager->getRepository(Adherent::class)->findByGenre('Féminin');

        dd($woman);
    }

    //Api en get pour récupérer l'adhérents sélectionner
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

    //Api en get pour récupérer la rencontre sélectionner
    #[
        Route('/api/meet/{id}', name: 'api_meet', methods: 'GET'),
        IsGranted('ROLE_USER')
    ]
    public function meet(Meet $meet ,SerializerInterface $serializer): Response
    {
        $meetApi = $this->entityManager->getRepository(Meet::class)->findBy(['id' => $meet->getId()]);

        return $this->json($meetApi, 200, [], ['groups' => 'meet:read']);

    }

    //Api en post pour créer une nouvelle rencontre
    #[
        Route('/api/new_meet/', name: 'api_new_meet', methods: 'POST'),
        IsGranted('ROLE_USER')
    ]
    public function newMeet(Request $request): Response
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        $woman = $this->entityManager->getRepository(Adherent::class)->findBy(['id' => $data['woman']]);
        $man = $this->entityManager->getRepository(Adherent::class)->findBy(['id' => $data['man']]);
        $date = new DateTimeImmutable($data['date']);
        $meet = new Meet();

        $meet->setAdherentWoman($woman[0]);
        $meet->setAdherentMan($man[0]);
        $meet->setStartedAt($date);

        $today = new DateTimeImmutable("now");

        if ($date > $today){
            $disponibility = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'status_meet', 'name' => 'En rencontre']);
            $woman[0]->setStatusMeet($disponibility[0]);
            $man[0]->setStatusMeet($disponibility[0]);

            $this->entityManager->persist($woman[0]);
            $this->entityManager->persist($man[0]);
            $this->entityManager->flush();
        }

        $this->entityManager->persist($meet);
        $this->entityManager->flush();

        return new JsonResponse(
            [
                'status' => 'ok',
                'meet' => $meet->getId()
            ],
            JsonResponse::HTTP_CREATED
        );
    }

    //Api en post pour ajouter des informations sur les rencontres
    #[
        Route('/api/update_meet/', name: 'api_update_meet', methods: 'POST'),
        IsGranted('ROLE_USER')
    ]
    public function infoMeet(Request $request): Response
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        $meet = $this->entityManager->getRepository(Meet::class)->findBy(['id' => $data['id']]);
        $options = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'status_meet']);
        $actions = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'action_meet']);

        if($data['status_meet_woman']){
            foreach ($options as $option){
                if ($option->getName() == $data['status_meet_woman']){
                    $meet[0]->setStatusMeetWoman($option);
                    $meet[0]->getAdherentWoman()->setStatusMeet($option);
                }
            }
        }

        if($data['status_meet_man']){
            foreach ($options as $option){
                if ($option->getName() == $data['status_meet_man']){
                    $meet[0]->setStatusMeetMan($option);
                    $meet[0]->getAdherentMan()->setStatusMeet($option);
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
        } else {
            $meet[0]->setCommentsWoman('');
        }

        if($data['comments_man']){
            $meet[0]->setCommentsMan($data['comments_man']);
        } else {
            $meet[0]->setCommentsMan('');
        }

        if($data['action_woman']){
            foreach ($actions as $action){
                if ($action->getName() == $data['action_woman']){
                    $meet[0]->setActionMeetWoman($action);
                }
            }
        }

        if($data['action_man']){
            foreach ($actions as $action){
                if ($action->getName() == $data['action_man']){
                    $meet[0]->setActionMeetMan($action);
                }
            }
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

    //Api en get pour récupérer la rencontre sélectionner et la delete
    #[
        Route('/api/meet/delete/{id}', name: 'api_delete_meet', methods: 'DELETE'),
        IsGranted('ROLE_USER')
    ]
    public function deleteMeet(Meet $meet ,SerializerInterface $serializer, Request $request): Response
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        $meets = $this->entityManager->getRepository(Meet::class)->findBy(['id' => $data['id']]);

        $this->entityManager->remove($meets[0]);
        $this->entityManager->flush();

        return new JsonResponse(
            [
                'status' => 'ok'
            ],
            JsonResponse::HTTP_CREATED
        );

    }
}
