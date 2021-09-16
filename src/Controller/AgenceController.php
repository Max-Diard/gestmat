<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Entity\User;
use App\Form\AgenceType;
use App\Form\ChangeAgenceType;
use App\Form\DelegationAgenceType;
use App\Repository\AgenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgenceController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[
        Route('/agence/{id}', name: 'agence_single'),
        IsGranted('ROLE_USER'),
    ]
    public function index(Agence $agence, Request $request): Response
    {
        // Gérer le fait que si l'agence n'est pas lier à l'utilisateur il ne peux pas y accéder

        $user = $this->getUser();
        $allAgence = $this->entityManager->getRepository(Agence::class)->findAll();
        $droitAgence = $agence->getDroitAgence();

        // dd($userAgence);

        // A changer de place et voir si cela fonctionne pas quand l'user n'est pas lier à l'agence
        if(count($allAgence) > 1){
            $agenceId = $agence->getId();
            for($i = 0; $i < count($allAgence); $i++){
                $otherAgence = $this->entityManager->getRepository(Agence::class)->findOtherAgence($agenceId);
            //     // dd($otherAgence);
            }
            // if(count($droitAgence) > 0){
            //     for ($i = 0; $i < count($allAgence); $i++){
            //         for ($j = 0; $j < count($droitAgence); $j++){
            //             if($allAgence[$i]->getName() == $droitAgence[$j]){
            //                 unset($allAgence[$i]);
            //             }
            //         }
            //         // dd($allAgence[0], $allAgence[1], $droitAgence[0]);
            //     //     for ($j = 0; $j < count($allAgence); $j++){
            //     //         if($allAgence[$j]->getName() == $droitAgence[$i]){
            //     //             unset($allAgence[$i]);
            //     //         }
            //     //     }
            //     }
            // }
            // dd($otherAgence);
            $form = $this->createForm(DelegationAgenceType::class, $agence, [
                'agences' => $otherAgence
            ]);

            $form->handleRequest($request);
            
            if($form->isSubmitted() && $form->isValid()){
                $delegation = $form->get('droit_agence')->getData();

                // dd($delegation);
                foreach($delegation as $deleg){
                    $agence->addDroitAgence($deleg);
                }
                

                $this->entityManager->persist($agence);
                $this->entityManager->flush();

                return $this->redirectToRoute('agence_single', ['id' => $agence->getId()]);
            }

            for ($i = 0; $i < count($agence->getUsers()); $i++){
                if($user == $agence->getUsers()[$i]){
                    return $this->render('agence/singleAgence.html.twig', [
                        'agence' => $agence,
                        'form' => $form->createView()
                    ]);
                }
            }
        }
        $this->denyAccessUnlessGranted('agence_edit', $agence);

        return $this->render('agence/singleAgence.html.twig', [
            'agence' => $agence,
            'form' => $form->createView()
        ]);
    }

    #[
        Route('/agence/{id}/modify', name: 'agence_modify'),
        IsGranted('ROLE_USER')
    ]
    public function modifyAgence(Agence $agence, Request $request): Response
    {
        $linkPic = $agence->getLinkPicture();

        $form = $this->createForm(ChangeAgenceType::class, $agence);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $agence = $form->getData();

            $picture = $form->get('link_picture')->getData();
            // Si une image est envoyé alors on ajoute l'information en BDD
            if($picture){
                if ($linkPic){
                    unlink($this->getParameter('agence_directory') . 'agence' . $agence->getId() . '/picture/' . $linkPic);
                }
                $pictureExt = $picture->guessExtension();
                $pictureName = md5(uniqid()) . '.' . $pictureExt;
                $picture->move($this->getParameter('agence_directory') . 'agence' . $agence->getId() . '/picture/', $pictureName);
                $agence->setLinkPicture($pictureName);
            } 
            // On récupére le nom de l'image déjà existant et on lui renvoi
            else {
                $agence->setLinkPicture($linkPic);
            } 

            $this->entityManager->persist($agence);
            $this->entityManager->flush();

            return $this->redirectToRoute('agence_single', ['id' => $agence->getId()]);
        }

        return $this->render('agence/agenceModify.html.twig', [
            'form' => $form->createView(),
            'agence' => $agence
        ]);
    }
}
