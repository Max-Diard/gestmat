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

        // dd($adherent->getLinkInformation());

        $linkInfo = $adherent->getLinkInformation();
        $linkContrat = $adherent->getLinkContrat();
        $linkPic = $adherent->getLinkPicture();
        $linkAnnouncement = $adherent->getLinkPictureAnnouncement();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $adherent = $form->getData();

            $fileInfo = $form->get('link_information')->getData();

            if($fileInfo){
                $fileInfoExt = $fileInfo->guessExtension();
                $fileInfoName = md5(uniqid()) . '.' . $fileInfoExt;
                $fileInfo->move($this->getParameter('information_directory'), $fileInfoName);
                $adherent->setLinkInformation($fileInfoName);
            } else {
                $adherent->setLinkInformation($linkInfo);
            } 

            $fileContrat = $form->get('link_contrat')->getData();

            if($fileContrat){
                $fileContratExt = $fileContrat->guessExtension();
                $fileContratName = md5(uniqid()) . '.' . $fileContratExt;
                $fileContrat->move($this->getParameter('document_directory'), $fileContratName);
                $adherent->setLinkContrat($fileContratName);
            } else {
                $adherent->setLinkContrat($linkContrat);
            }

            $fileAnnouncement = $form->get('link_picture_announcement')->getData();

            // dd($fileAnnouncement);

            if ($fileAnnouncement){
                $fileAnnouncementExt = $fileAnnouncement->guessExtension();
                $fileAnnouncementName = md5(uniqid()) . '.' . $fileAnnouncementExt;
                $fileAnnouncement->move($this->getParameter('announcement_directory'), $fileAnnouncementName);
                $adherent->setLinkPictureAnnouncement($fileAnnouncementName);
            } else {
                $adherent->setLinkPictureAnnouncement($linkAnnouncement);
            }

            $filePic = $form->get('link_picture')->getData();

            if ($filePic){
                $filePicExt = $filePic->guessExtension();
                $filePicName = md5(uniqid()).'.'.$filePicExt;
                $filePic->move($this->getParameter('picture_directory'), $filePicName);
                $adherent->setLinkPicture($filePicName);
            } else {
                $adherent->setLinkPicture($linkPic);
            }

            $this->entityManager->persist($adherent);
            $this->entityManager->flush();
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
            if($fileInfo){
                $fileInfoExt = $fileInfo->guessExtension();
                $fileInfoName = md5(uniqid()) . '.' . $fileInfoExt;
                $fileInfo->move($this->getParameter('information_directory'), $fileInfoName);
                $adherent->setLinkInformation($fileInfoName);
            }

            $fileContrat = $form->get('link_contrat')->getData();
            if($fileContrat){
                $fileContratExt = $fileContrat->guessExtension();
                $fileContratName = md5(uniqid()) . '.' . $fileContratExt;
                $fileContrat->move($this->getParameter('document_directory'), $fileContratName);
                $adherent->setLinkContrat($fileContratName);
            }

            $fileAnnouncement = $form->get('link_picture_announcement')->getData();
            if($fileAnnouncement){
                $fileAnnouncementExt = $fileAnnouncement->guessExtension();
                $fileAnnouncementName = md5(uniqid()) . '.' . $fileAnnouncementExt;
                $fileAnnouncement->move($this->getParameter('announcement_directory'), $fileAnnouncementName);
                $adherent->setLinkPictureAnnouncement($fileAnnouncementName);
            }

            $filePic = $form->get('link_picture')->getData();
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
