<?php

namespace App\Controller;

use App\Entity\Adherent;
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
    #[Route('/api/adherent/{id}', name: 'api_adherent', methods: 'GET')]
    public function index(Adherent $adherent ,SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        $adherentApi = $entityManager->getRepository(Adherent::class)->findBy(['id' => $adherent->getId()]);

        //Gestion de l'api
        
        $response = $this->json($adherentApi, 200, [], ['groups' => 'adherent:read']);

        return $response;
        
    }
}
