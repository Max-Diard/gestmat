<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Form\AdherentType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdherentController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    //Liste de tous les adhérents
    #[
        Route('/adherent', name: 'adherent_all'),
        IsGranted('ROLE_USER')
    ]
    public function index(): Response
    {

        $womenAdherent = $this->entityManager->getRepository(Adherent::class)->findByGenre('Féminin');
        $menAdherent = $this->entityManager->getRepository(Adherent::class)->findByGenre('Masculin');

        return $this->render('adherent/index.html.twig', [
            'womenAdherent' => $womenAdherent,
            'menAdherent' => $menAdherent
        ]);
    }

    //Information de l'adhérent sélectionné
    #[
        Route('/adherent/profil/{id}', name: 'adherent_single')
    ]
    public function allAdherent(Adherent $adherent, Request $request): Response
    {
        $form = $this->createForm(AdherentType::class, $adherent);

        // On récupére le nom des fichiers déjà existant
        $linkInfo = $adherent->getLinkInformation();
        $linkContract = $adherent->getLinkContract();
        $linkPic = $adherent->getLinkPicture();
        $linkAnnouncement = $adherent->getLinkPictureAnnouncement();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $adherent = $form->getData();

            $fileInfo = $form->get('link_information')->getData();

            // Gestion des fichiers
            // Si une image est envoyé alors on ajoute l'information en BDD
            if($fileInfo){
                // Si l'adhérent à déjà une image on supprime cette dernière du dossier 'public'
                if ($linkInfo){
                    unlink($this->getParameter('adherent_directory') . 'adherent' . $adherent->getId() . '/information/' . $linkInfo);
                }
                $fileInfoExt = $fileInfo->guessExtension();
                $fileInfoName = md5(uniqid()) . '.' . $fileInfoExt;
                $fileInfo->move($this->getParameter('adherent_directory') . 'adherent' . $adherent->getId() . '/information/', $fileInfoName);
                $adherent->setLinkInformation($fileInfoName);
            } 
            // On récupére le nom de l'image déjà existant et on lui renvoi
            else {
                $adherent->setLinkInformation($linkInfo);
            } 

            $fileContract = $form->get('link_contract')->getData();

            // Si une image est envoyé alors on ajoute l'information en BDD
            if($fileContract){
                // Si l'adhérent à déjà une image on supprime cette dernière du dossier 'public'
                if ($linkContract){
                    unlink($this->getParameter('adherent_directory') . 'adherent' . $adherent->getId() . '/contract/' . $linkContract);
                }
                $fileContractExt = $fileContract->guessExtension();
                $fileContractName = md5(uniqid()) . '.' . $fileContractExt;
                $fileContract->move($this->getParameter('adherent_directory') . 'adherent' . $adherent->getId() . '/contract/', $fileContractName);
                $adherent->setLinkContract($fileContractName);
            }
            // On récupére le nom de l'image déjà existant et on lui renvoi 
            else {
                $adherent->setLinkContract($linkContract);
            }

            $fileAnnouncement = $form->get('link_picture_announcement')->getData();

            // Si une image est envoyé alors on ajoute l'information en BDD
            if ($fileAnnouncement){
                // Si l'adhérent à déjà une image on supprime cette dernière du dossier 'public'
                if ($linkAnnouncement){
                    unlink($this->getParameter('adherent_directory') . 'adherent' . $adherent->getId() . '/announcement/' . $linkAnnouncement);
                }
                $fileAnnouncementExt = $fileAnnouncement->guessExtension();
                $fileAnnouncementName = md5(uniqid()) . '.' . $fileAnnouncementExt;
                $fileAnnouncement->move($this->getParameter('adherent_directory') . 'adherent' . $adherent->getId() . '/announcement/', $fileAnnouncementName);
                $adherent->setLinkPictureAnnouncement($fileAnnouncementName);
            }
            // On récupére le nom de l'image déjà existant et on lui renvoi
             else {
                $adherent->setLinkPictureAnnouncement($linkAnnouncement);
            }

            $filePic = $form->get('link_picture')->getData();

            // Si une image est envoyé alors on ajoute l'information en BDD
            if ($filePic){
                // Si l'adhérent à déjà une image on supprime cette dernière du dossier 'public'
                if ($linkPic){
                    unlink($this->getParameter('adherent_directory') . 'adherent' . $adherent->getId() . '/picture/' . $linkPic);
                }
                $filePicExt = $filePic->guessExtension();
                $filePicName = md5(uniqid()).'.'.$filePicExt;
                $filePic->move($this->getParameter('adherent_directory') . 'adherent' . $adherent->getId() . '/picture/', $filePicName);
                // $filePic->move($this->getParameter('picture_directory'), $filePicName);
                $adherent->setLinkPicture($filePicName);
            } 
            // On récupére le nom de l'image déjà existant et on lui renvoi
            else {
                $adherent->setLinkPicture($linkPic);
            }

            $this->entityManager->persist($adherent);
            $this->entityManager->flush();

            $this->addFlash('succesModifyAdherent', 'L\'adhérent à bien était modifier !');

            $this->redirectToRoute('adherent_single', ['id' => $adherent->getId()]);
        }

        return $this->render('adherent/singleAdherent.html.twig', [
            'adherentProfile' => $adherent,
            'formAdherent' => $form->createView()
        ]);
    }

    //Page pour ajouter un adhérent
    #[
        Route('/adherent/add', name: 'adherent_add'),
        IsGranted('ROLE_USER')
    ]
    public function addAdherent(Request $request): Response
    {
        $user = $this->getUser();
        $agence = $user->getAgence();

        $lastAdherent = $this->entityManager->getRepository(Adherent::class)->findByLastId();
        $lastAdherent = $lastAdherent[0]->getId();

        if ($lastAdherent != ''){
            $lastAdherent++;
        } else{
            $lastAdherent = 1;
        }

        $adherent = new Adherent();

        $form = $this->createForm(AdherentType::class, $adherent);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $adherent = $form->getData();

            $agence = $this->getUser();

            // TODO: Gérer si plusieurs agences

            $adherent->setAgence($user->getAgence()[0]);

            $fileInfo = $form->get('link_information')->getData();
            // Si une image est envoyé alors on ajoute l'information en BDD
            if($fileInfo){
                $fileInfoExt = $fileInfo->guessExtension();
                $fileInfoName = md5(uniqid()) . '.' . $fileInfoExt;
                $fileInfo->move($this->getParameter('adherent_directory') . 'adherent' . $lastAdherent . '/information/', $fileInfoName);
                $adherent->setLinkInformation($fileInfoName);
            }

            $fileContract = $form->get('link_contract')->getData();
            // Si une image est envoyé alors on ajoute l'information en BDD
            if($fileContract){
                $fileContractExt = $fileContract->guessExtension();
                $fileContractName = md5(uniqid()) . '.' . $fileContractExt;
                $fileContract->move($this->getParameter('adherent_directory') . 'adherent' . $lastAdherent . '/contract/', $fileContractName);
                $adherent->setLinkContract($fileContractName);
            }

            $fileAnnouncement = $form->get('link_picture_announcement')->getData();
            // Si une image est envoyé alors on ajoute l'information en BDD
            if($fileAnnouncement){
                $fileAnnouncementExt = $fileAnnouncement->guessExtension();
                $fileAnnouncementName = md5(uniqid()) . '.' . $fileAnnouncementExt;
                $fileAnnouncement->move($this->getParameter('adherent_directory') . 'adherent' . $lastAdherent . '/announcement/', $fileAnnouncementName);
                $adherent->setLinkPictureAnnouncement($fileAnnouncementName);
            }

            $filePic = $form->get('link_picture')->getData();
            // Si une image est envoyé alors on ajoute l'information en BDD
            if($filePic){
                $filePicExt = $filePic->guessExtension();
                $filePicName = md5(uniqid()).'.'.$filePicExt;
                $filePic->move($this->getParameter('adherent_directory') . 'adherent' . $lastAdherent . '/picture/', $filePicName);
                $adherent->setLinkPicture($filePicName);
            }
            
            $this->entityManager->persist($adherent);
            $this->entityManager->flush();

            $this->addFlash(
                'successNewAdherent',
                'Félicitations, vous avez créer un nouvel adhérent !'
            );
            return $this->redirectToRoute('adherent_all');
        }

        return $this->render('adherent/addAdherent.html.twig', [
            'formAdherent' => $form->createView(),
            'agences' => $agence
        ]);
    }
}
