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

    //Page pour voir l'agence sélectionné
    #[
        Route('/agence/{id}', name: 'agence_single'),
        IsGranted('ROLE_USER'),
    ]
    public function index(Agence $agence, Request $request): Response
    {
        $user = $this->getUser();

        $allAgence = $this->entityManager->getRepository(Agence::class)->findAll();

        if(count($allAgence) > 1){
            $agenceId = $agence->getId();

//            for($i = 0; $i < count($allAgence); $i++){
//                $otherAgences = $this->entityManager->getRepository(Agence::class)->findOtherAgence($agenceId);
//            }
//
//            $agenceGiveDroit = [];
//
//            foreach($otherAgences as $otherAgence){
//                for($j = 0; $j < count($allAgence); $j++){
//                    if($otherAgence->getDroitAgence()[$j] === $agence){
//                        $agenceGiveDroit[] = $otherAgence;
//                    }
//                }
//            }
//
//            $form = $this->createForm(DelegationAgenceType::class, $agence, [
//                'agences' => $otherAgences
//            ]);
//
//            $form->handleRequest($request);
//
//            if($form->isSubmitted() && $form->isValid()){
//                $delegation = $form->get('droit_agence')->getData();
//
//                foreach($delegation as $deleg){
//                    $agence->addDroitAgence($deleg);
//                }
//
//                $this->entityManager->persist($agence);
//                $this->entityManager->flush();
//
//                return $this->redirectToRoute('agence_single', ['id' => $agence->getId()]);
//            }

            for ($i = 0; $i < count($agence->getUsers()); $i++){
                if($user == $agence->getUsers()[$i]){
                    return $this->render('agence/singleAgence.html.twig', [
                        'agence' => $agence,
//                        'form' => $form->createView(),
//                        'agenceGiveDroit' => $agenceGiveDroit
                    ]);
                }
            }
        }

        $this->denyAccessUnlessGranted('agence_edit', $agence);
        return $this->render('bundles/TwigBundle/Exception/error403.html.twig');
    }

    //Page pour modifier l'agence sélectionner
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
            // Si une image est envoyée alors on ajoute l'information en BDD
            if($picture){
                if ($linkPic){
                    unlink($this->getParameter('agence_directory') . 'agence' . $agence->getId() . '/picture/' . $linkPic);
                }
                $pictureExt = $picture->guessExtension();
                $pictureName = md5(uniqid('', true)) . '.' . $pictureExt;
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
