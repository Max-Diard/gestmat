<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Form\AgenceType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgenceController extends AbstractController
{
    // public function __construct(EntityManagerInterface $entityManager)
    // {
    //     $this->entityManager = $entityManager;
    // }

    // #[
    //     Route('/agence', name: 'agence'),
    //     IsGranted("ROLE_ADMIN")
    // ]
    // public function index(): Response
    // {
    //     $agence = $this->entityManager->getRepository(Agence::class)->findAll();

    //     return $this->render('agence/index.html.twig', [
    //         'allAgence' => $agence
    //     ]);
    // }

    // #[
    //     Route('/agence/add', name: 'agence_add'),
    //     IsGranted("ROLE_ADMIN")
    // ]
    // public function newAgence(Request $request): Response
    // {
    //     $lastAgence = $this->entityManager->getRepository(Agence::class)->findByLastId();
    //     $lastAgence = $lastAgence[0]->getId();

    //     if ($lastAgence != ''){
    //         $lastAgence++;
    //     } else{
    //         $lastAgence = 1;
    //     }
    //     $agence = new Agence();

    //     $form = $this->createForm(AgenceType::class, $agence);

    //     $form->handleRequest($request);

    //     if($form->isSubmitted() && $form->isValid()){
    //         $agence = $form->getData();

    //         $picture = $form->get('link_picture')->getData();
    //         // Si une image est envoyé alors on ajoute l'information en BDD
    //         if($picture){
    //             $pictureExt = $picture->guessExtension();
    //             $pictureName = md5(uniqid()) . '.' . $pictureExt;
    //             $picture->move($this->getParameter('agence_directory') . 'agence' . $lastAgence . '/picture/', $pictureName);
    //             $agence->setLinkPicture($pictureName);
    //         }

    //         $this->entityManager->persist($agence);
    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('agence');
    //     }

    //     return $this->render('agence/add.html.twig', [
    //         'form' => $form->createView()
    //     ]);
    // }

    // #[
    //     Route('/agence/{id}', name: 'agence_single'),
    //     IsGranted("ROLE_ADMIN")
    // ]
    // public function singleAgence(Agence $agence, Request $request): Response
    // {
    //     return $this->render('agence/single.html.twig', [
    //         'agence' => $agence
    //     ]);
    // }
}
