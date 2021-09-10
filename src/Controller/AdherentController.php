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
    public function index(Request $request): Response
    {

        $womanAdherent = $this->entityManager->getRepository(Adherent::class)->findByGenre('Féminin');
        $manAdherent = $this->entityManager->getRepository(Adherent::class)->findByGenre('Masculin');

        return $this->render('adherent/index.html.twig', [
            'womanAdherent' => $womanAdherent,
            'manAdherent' => $manAdherent
        ]);
    }

    //Information de l'adhérents sélectionné
    #[
        Route('/adherent/profil/{id}', name: 'adherent_single')
    ]
    public function allAdherent(Adherent $adherent, Request $request): Response
    {
        $form = $this->createForm(AdherentType::class, $adherent);

        // On récupére le nom des fichiers déjà existant
        $linkInfo = $adherent->getLinkInformation();
        $linkContrat = $adherent->getLinkContrat();
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
                    unlink($this->getParameter('information_directory') . $linkInfo);
                }
                $fileInfoExt = $fileInfo->guessExtension();
                $fileInfoName = md5(uniqid()) . '.' . $fileInfoExt;
                $fileInfo->move($this->getParameter('information_directory'), $fileInfoName);
                $adherent->setLinkInformation($fileInfoName);
            } 
            // On récupére le nom de l'image déjà existant et on lui renvoi
            else {
                $adherent->setLinkInformation($linkInfo);
            } 

            $fileContrat = $form->get('link_contrat')->getData();

            // Si une image est envoyé alors on ajoute l'information en BDD
            if($fileContrat){
                // Si l'adhérent à déjà une image on supprime cette dernière du dossier 'public'
                if ($linkContrat){
                    unlink($this->getParameter('document_directory') . $linkContrat);
                }
                $fileContratExt = $fileContrat->guessExtension();
                $fileContratName = md5(uniqid()) . '.' . $fileContratExt;
                $fileContrat->move($this->getParameter('document_directory'), $fileContratName);
                $adherent->setLinkContrat($fileContratName);
            }
            // On récupére le nom de l'image déjà existant et on lui renvoi 
            else {
                $adherent->setLinkContrat($linkContrat);
            }

            $fileAnnouncement = $form->get('link_picture_announcement')->getData();

            // Si une image est envoyé alors on ajoute l'information en BDD
            if ($fileAnnouncement){
                // Si l'adhérent à déjà une image on supprime cette dernière du dossier 'public'
                if ($linkAnnouncement){
                    unlink($this->getParameter('announcement_directory') . $linkAnnouncement);
                }
                $fileAnnouncementExt = $fileAnnouncement->guessExtension();
                $fileAnnouncementName = md5(uniqid()) . '.' . $fileAnnouncementExt;
                $fileAnnouncement->move($this->getParameter('announcement_directory'), $fileAnnouncementName);
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
                    unlink($this->getParameter('picture_directory') . $linkPic);
                }
                $filePicExt = $filePic->guessExtension();
                $filePicName = md5(uniqid()).'.'.$filePicExt;
                $filePic->move($this->getParameter('picture_directory'), $filePicName);
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

        $adherent = new Adherent();

        $form = $this->createForm(AdherentType::class, $adherent);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $adherent = $form->getData();

            $agence = $this->getUser();

            $adherent->setAgence($agence);

            $fileInfo = $form->get('link_information')->getData();
            // Si une image est envoyé alors on ajoute l'information en BDD
            if($fileInfo){
                $fileInfoExt = $fileInfo->guessExtension();
                $fileInfoName = md5(uniqid()) . '.' . $fileInfoExt;
                $fileInfo->move($this->getParameter('information_directory'), $fileInfoName);
                $adherent->setLinkInformation($fileInfoName);
            }

            $fileContrat = $form->get('link_contrat')->getData();
            // Si une image est envoyé alors on ajoute l'information en BDD
            if($fileContrat){
                $fileContratExt = $fileContrat->guessExtension();
                $fileContratName = md5(uniqid()) . '.' . $fileContratExt;
                $fileContrat->move($this->getParameter('document_directory'), $fileContratName);
                $adherent->setLinkContrat($fileContratName);
            }

            $fileAnnouncement = $form->get('link_picture_announcement')->getData();
            // Si une image est envoyé alors on ajoute l'information en BDD
            if($fileAnnouncement){
                $fileAnnouncementExt = $fileAnnouncement->guessExtension();
                $fileAnnouncementName = md5(uniqid()) . '.' . $fileAnnouncementExt;
                $fileAnnouncement->move($this->getParameter('announcement_directory'), $fileAnnouncementName);
                $adherent->setLinkPictureAnnouncement($fileAnnouncementName);
            }

            $filePic = $form->get('link_picture')->getData();
            // Si une image est envoyé alors on ajoute l'information en BDD
            if($filePic){
                $filePicExt = $filePic->guessExtension();
                $filePicName = md5(uniqid()).'.'.$filePicExt;
                $filePic->move($this->getParameter('picture_directory'), $filePicName);
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
            'formAdherent' => $form->createView()
        ]);
    }
}
