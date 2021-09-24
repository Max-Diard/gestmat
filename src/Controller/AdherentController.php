<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Entity\AdherentOption;
use App\Entity\Agence;
use App\Entity\Meet;
use App\Entity\Search;
use App\Form\AdherentType;
use App\Form\MeetType;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdherentController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager, Container $container)
    {
    }

    //Liste de tous les adhérents
    #[
        Route('/adherent', name: 'adherent_all'),
        IsGranted('ROLE_USER')
    ]
    public function index(): Response
    {
        $agences = $this->getUser()->getAgence();

        // On récupére les status meet pour ajouter dans le form de la modal
        $options = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'status_meet']);

        //Si l'utilisateur connecté à plus d'une agence, on itére dessus
        if (count($agences) > 0){
            for($i = 0; $i < count($agences); $i++){
                $agence = $agences[$i]->getId();
                
                $womenAdherent[] = $this->entityManager->getRepository(Adherent::class)->findByGenreAgences('Féminin', $agence);
                $menAdherent[] = $this->entityManager->getRepository(Adherent::class)->findByGenreAgences('Masculin', $agence);

                $fullAdherent = [
                    $i => $womenAdherent,
                    $i + 1 => $menAdherent
                ];

                $otherAgences = $this->entityManager->getRepository(Agence::class)->findOtherAgence($agences[$i]);

                $womenAdherentDroit = [];
                $menAdherentDroit = [];

                // On regarde si il y a plus d'agences dans la BDD que l'user à moins d'agences lier
                if(count($otherAgences) > count($agences)){
                    foreach($otherAgences as $otherAgence){
                        $allAgence = $otherAgence->getDroitAgence();

                        if(count($allAgence) > 0){
                            for($j = 0; $j < count($allAgence); $j++){
                                if($allAgence[$j] === $agences[$i]){
                                    $womenAdherentDroit[] = $this->entityManager->getRepository(Adherent::class)->findByGenreAgences('Féminin', $otherAgence);
                                    $menAdherentDroit[] = $this->entityManager->getRepository(Adherent::class)->findByGenreAgences('Masculin', $otherAgence);

                                    $fullAdherent += [
                                        $j + 2 => $womenAdherentDroit,
                                        $j + 3 => $menAdherentDroit
                                    ];
                                }
                            }
                        }
                    }
                }
                // On récupére toute les rencontres et on met dans un tableau si l'id de l'adhérent à déjà eu une rencontre
                $meeting = [];

                foreach ($fullAdherent as $agenceAdherent){
                    foreach ($agenceAdherent as $adherents){
                        foreach ($adherents as $adherent){
                            $haveMeet = $this->entityManager->getRepository(Meet::class)->findBy(['adherent_man' => $adherent->getId()]);
                            if (empty($haveMeet)){
                                $haveMeet = $this->entityManager->getRepository(Meet::class)->findBy(['adherent_woman' => $adherent->getId()]);
                            }
                            if(!empty($haveMeet)){
                                $meeting[] = $adherent->getId();
                            }
                        }
                    }
                }
            }
        } else {
            $womenAdherent = '';
            $menAdherent = '';
            $womenAdherentDroit = [];
            $menAdherentDroit = [];
        }
        return $this->render('adherent/index.html.twig', [
            'womenAdherent'         => $womenAdherent,
            'menAdherent'           => $menAdherent,
            'womenAdherentDroit'    => $womenAdherentDroit,
            'menAdherentDroit'      => $menAdherentDroit,
            'meet'                  => $meeting,
            'options'               => $options
        ]);
    }

    //Information de l'adhérent sélectionné
    #[
        Route('/adherent/profil/{id}', name: 'adherent_single'),
        IsGranted('ROLE_USER')
    ]
    public function adherentSingle(Adherent $adherent, Request $request): Response
    {
        // On regarde si l'user fait bien parti de l'agence de l'adhérent
        $agenceAdherent = $adherent->getAgence();
        $userAgence = $this->getUser()->getAgence();

        foreach ($userAgence as $idAgence) {
            $agenceIdUser[] = $idAgence->getId();
        }

        // True ou false pour savoir si c'est l'user fait parti de l'agence
        if(in_array($agenceAdherent->getId(), $agenceIdUser) ){
            $trueAgence = true;
        } else {
            $trueAgence = false;
        }

        $form = $this->createForm(AdherentType::class, $adherent);

        // On récupére le nom des fichiers déjà existant
        $linkInfo = $adherent->getLinkInformation();
        $linkContract = $adherent->getLinkContract();
        $linkPic = $adherent->getLinkPicture();
        $linkAnnouncement = $adherent->getLinkPictureAnnouncement();

        //Affichage des meets

        if ($adherent->getGenre()->getName() === 'Féminin'){
            $genre = 'adherent_woman';
        } else {
            $genre = 'adherent_man';
        }

        // On récupére les rencontres de l'adhérent
        $meets = $this->entityManager->getRepository(Meet::class)->findBy([$genre => $adherent->getId()]);

        // On récupére les status meet pour ajouter dans le form de la modal
        $options = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'status_meet']);

        $form->handleRequest($request);

        // Envoi du formulaire
        if($form->isSubmitted() && $form->isValid()){
            $adherent = $form->getData();

            // Gestion des fichiers
            $fileInfo = $form->get('link_information')->getData();

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
            'adherentProfile'   => $adherent,
            'formAdherent'      => $form->createView(),
            'trueAgence'        => $trueAgence,
            'meets'             => $meets,
            'options'           => $options
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

        $lastAdherent = $this->entityManager->getRepository(Adherent::class)->findByLastId();
        $lastAdherent = $lastAdherent[0]->getId();

        if ($lastAdherent != ''){
            $lastAdherent++;
        } else{
            $lastAdherent = 1;
        }

        $adherent = new Adherent();
        $agences = $user->getAgence();

        $form = $this->createForm(AdherentType::class, $adherent, [
            'agences' => $agences
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $adherent = $form->getData();

            $agence = $this->getUser();

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
        ]);
    }

}
