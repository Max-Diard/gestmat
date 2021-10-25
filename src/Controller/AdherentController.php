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
use Dompdf\Dompdf;
use Dompdf\Options;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdherentController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager)
    {}

    //Liste de tous les adhérents
    #[
        Route('/adherent', name: 'adherent_all'),
        IsGranted('ROLE_USER')
    ]
    public function index(Request $request): Response
    {
        $agences = $this->getUser()->getAgence();

        // On récupére les status meet pour ajouter dans le form de la modal
        $options = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'status_meet']);
        $actions = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'action_meet']);

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

//                $otherAgences = $this->entityManager->getRepository(Agence::class)->findOtherAgence($agences[$i]);
//
//                $womenAdherentDroit = [];
//                $menAdherentDroit = [];

                // On regarde si il y a plus d'agences dans la BDD que l'user à moins d'agences lier
//                if(count($otherAgences) > count($agences)){
//                    foreach($otherAgences as $otherAgence){
//                        $allAgence = $otherAgence->getDroitAgence();
//
//                        if(count($allAgence) > 0){
//                            for($j = 0; $j < count($allAgence); $j++){
//                                if($allAgence[$j] === $agences[$i]){
//                                    $womenAdherentDroit[] = $this->entityManager->getRepository(Adherent::class)->findByGenreAgences('Féminin', $otherAgence);
//                                    $menAdherentDroit[] = $this->entityManager->getRepository(Adherent::class)->findByGenreAgences('Masculin', $otherAgence);
//
//                                    $fullAdherent += [
//                                        $j + 2 => $womenAdherentDroit,
//                                        $j + 3 => $menAdherentDroit
//                                    ];
//                                }
//                            }
//                        }
//                    }
//                }
                // On récupére toute les rencontres et on met dans un tableau si l'id de l'adhérent à déjà eu une rencontre
                $meeting = [];

                foreach ($fullAdherent as $agenceAdherent){
                    foreach ($agenceAdherent as $adherents){
                        foreach ($adherents as $adherent){
                            $haveMeet = $this->entityManager->getRepository(Meet::class)->findBy(['adherent_man' => $adherent->getId()]);
                            // Si pas de rencontre on regarde du côté des adhérents femme
                            if (empty($haveMeet)){
                                $haveMeet = $this->entityManager->getRepository(Meet::class)->findBy(['adherent_woman' => $adherent->getId()]);
                            }
                            //Si il n'y a l'id de l'adhérent du côté femme et homme alors on défini sont statut sur disponible
//                            if (empty($haveMeet)){
//                                // On regarde si la personne n'est pas en attente pour ne pas lui changer le statut
//                                if($adherent->getStatusMeet()->getName() != 'En attente'){
//                                    $dispo = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'status_meet', 'name' => 'Disponible']);
//                                    $adherent->setStatusMeet($dispo[0]);
//
//                                    $this->entityManager->persist($adherent);
//                                    $this->entityManager->flush();
//                                }
//                            }
                            if(!empty($haveMeet)){
                                $meeting[] = $adherent->getId();

                                //Pour modifier si tu le status meet pour l'adhérent
                                $dates = [];
                                foreach ($haveMeet as $m){
                                    $dates[] = ($m->getStartedAt());
                                }
//                                $dateLastMeet = $dates[count($dates) -1]->format('Y-m-d');
//                                $today = date('Y-m-d');
//
//                                if ($dateLastMeet >= $today){
//                                    $inMeet = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'status_meet', 'name' => 'En rencontre']);
//                                    $adherent->setStatusMeet($inMeet[0]);
//                                } else {
//                                    $dispo = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'status_meet', 'name' => 'Disponible']);
//                                    $adherent->setStatusMeet($dispo[0]);
//                                }
//                                $this->entityManager->persist($adherent);
//                                $this->entityManager->flush();
                            }
                        }
                    }
                }
            }
        } else {
            $womenAdherent = '';
            $menAdherent = '';
            $meeting = "";
//            $womenAdherentDroit = [];
//            $menAdherentDroit = [];
        }
        return $this->render('adherent/index.html.twig', [
            'womenAdherent'         => $womenAdherent,
            'menAdherent'           => $menAdherent,
//            'womenAdherentDroit'    => $womenAdherentDroit,
//            'menAdherentDroit'      => $menAdherentDroit,
            'meet'                  => $meeting,
            'options'               => $options,
            'actions'               => $actions,
            'agences'               => $agences
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
        $userAgence = $this->getUser()->getAgence();

        if(count($userAgence) > 0){

            $agenceAdherent = $adherent->getAgence();

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

            if(empty($meets)){
                if($adherent->getStatusMeet()->getName() != 'En attente'){
                    $status = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'status_meet']);
                    foreach($status as $stat){
                        if($stat->getName() == "Disponible" ){
                            $adherent->setStatusMeet($stat);
                            $this->entityManager->persist($adherent);
                            $this->entityManager->flush();
                        }
                    }
                }
            }

            // On récupére les status meet pour ajouter dans le form de la modal
            $options = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'status_meet']);
            $actions = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'action_meet']);

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
                    $fileInfoName = md5(uniqid('', true)) . '.' . $fileInfoExt;
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
                    $fileContractName = md5(uniqid('', true)) . '.' . $fileContractExt;
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
                    $fileAnnouncementName = md5(uniqid('', true)) . '.' . $fileAnnouncementExt;
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
                    $filePicName = md5(uniqid('', true)).'.'.$filePicExt;
                    $filePic->move($this->getParameter('adherent_directory') . 'adherent' . $adherent->getId() . '/picture/', $filePicName);
                    $adherent->setLinkPicture($filePicName);
                }
                // On récupére le nom de l'image déjà existant et on lui renvoi
                else {
                    $adherent->setLinkPicture($linkPic);
                }

                $this->entityManager->persist($adherent);
                $this->entityManager->flush();

                $this->addFlash('succesModifyAdherent', 'L\'adhérent(e) a bien été modifié(e)');

                $this->redirectToRoute('adherent_single', ['id' => $adherent->getId()]);
            }

            return $this->render('adherent/singleAdherent.html.twig', [
                'adherentProfile'   => $adherent,
                'formAdherent'      => $form->createView(),
                'trueAgence'        => $trueAgence,
                'meets'             => $meets,
                'options'           => $options,
                'actions'           => $actions
            ]);
        } else {
            $this->addFlash('noAgence', 'Vous n\'avez pas encore d\'agence associé à votre compte, merci de contacter l\'administrateur !');
            return $this->redirectToRoute('adherent_all');
        }
    }

    //Page pour ajouter un adhérent
    #[
        Route('/adherent/add', name: 'adherent_add'),
        IsGranted('ROLE_USER')
    ]
    public function addAdherent(Request $request): Response
    {
        $agences = $this->getUser()->getAgence();

        if (count($agences) > 0){
            $lastAdherent = $this->entityManager->getRepository(Adherent::class)->findByLastId();
            $lastAdherent = $lastAdherent[0]->getId();

            if ($lastAdherent != ''){
                $lastAdherent++;
            } else{
                $lastAdherent = 1;
            }

            $adherent = new Adherent();

            $form = $this->createForm(AdherentType::class, $adherent, [
                'agences' => $agences
            ]);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $adherent = $form->getData();

                $adherent->setActive(true);

                $fileInfo = $form->get('link_information')->getData();
                // Si une image est envoyé alors on ajoute l'information en BDD
                if($fileInfo){
                    $fileInfoExt = $fileInfo->guessExtension();
                    $fileInfoName = md5(uniqid('', true)) . '.' . $fileInfoExt;
                    $fileInfo->move($this->getParameter('adherent_directory') . 'adherent' . $lastAdherent . '/information/', $fileInfoName);
                    $adherent->setLinkInformation($fileInfoName);
                }

                $fileContract = $form->get('link_contract')->getData();
                // Si une image est envoyé alors on ajoute l'information en BDD
                if($fileContract){
                    $fileContractExt = $fileContract->guessExtension();
                    $fileContractName = md5(uniqid('', true)) . '.' . $fileContractExt;
                    $fileContract->move($this->getParameter('adherent_directory') . 'adherent' . $lastAdherent . '/contract/', $fileContractName);
                    $adherent->setLinkContract($fileContractName);
                }

                $fileAnnouncement = $form->get('link_picture_announcement')->getData();
                // Si une image est envoyé alors on ajoute l'information en BDD
                if($fileAnnouncement){
                    $fileAnnouncementExt = $fileAnnouncement->guessExtension();
                    $fileAnnouncementName = md5(uniqid('', true)) . '.' . $fileAnnouncementExt;
                    $fileAnnouncement->move($this->getParameter('adherent_directory') . 'adherent' . $lastAdherent . '/announcement/', $fileAnnouncementName);
                    $adherent->setLinkPictureAnnouncement($fileAnnouncementName);
                }

                $filePic = $form->get('link_picture')->getData();
                // Si une image est envoyé alors on ajoute l'information en BDD
                if($filePic){
                    $filePicExt = $filePic->guessExtension();
                    $filePicName = md5(uniqid('', true)).'.'.$filePicExt;
                    $filePic->move($this->getParameter('adherent_directory') . 'adherent' . $lastAdherent . '/picture/', $filePicName);
                    $adherent->setLinkPicture($filePicName);
                }

                $this->entityManager->persist($adherent);
                $this->entityManager->flush();

                $this->addFlash(
                    'successNewAdherent',
                    'Félicitations, vous avez créer un nouvel adhérent !'
                );
                return $this->redirectToRoute('adherent_single', ['id' => $adherent->getId()]);
            }

            return $this->render('adherent/singleAdherent.html.twig', [
                'formAdherent' => $form->createView(),
            ]);
        } else {
            $this->addFlash('noAgence', 'Vous n\'avez pas encore d\'agence associé à votre compte, merci de contacter l\'administrateur !');
            return $this->redirectToRoute('adherent_all');
        }
    }

    //Page pour voir toutes les rencontres de l'adhérent sélectionné
    #[
        Route('/adherent/profile/{id}/meet/all', name: 'adherent_profile_meet_all'),
        IsGranted('ROLE_USER')
    ]
    public function adherentMeetAll(Adherent $adherent): Response
    {
        if(count($this->getUser()->getAgence()) > 0) {

            if($adherent->getGenre()->getName() == 'Féminin'){
                $meets = $this->entityManager->getRepository(Meet::class)->findBy(['adherent_woman' => $adherent->getId()]);
            } else {
                $meets = $this->entityManager->getRepository(Meet::class)->findBy(['adherent_man' => $adherent->getId()]);
            }

            $options = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'status_meet']);
            $actions = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'action_meet']);


            return $this->render('adherent/allMeet.html.twig', [
                'adherent'  => $adherent,
                'meets'     => $meets,
                'options'   => $options,
                'actions'   => $actions
            ]);
        } else {
            $this->addFlash('noAgence', 'Vous n\'avez pas encore d\'agence associé à votre compte, merci de contacter l\'administrateur !');
            return $this->redirectToRoute('adherent_all');
        }

    }

    //Route pour télécharger la demande de témoignage
    #[
        Route('/adherent/profile/{id}/testimony', name: 'adherent_testimony'),
        IsGranted('ROLE_USER')
    ]
    public function testimonyAdherent(Adherent $adherent, Request $request, SluggerInterface $slugger)
    {
        $pdf = new Options();
        $pdf->set('defaultFont', 'Arial');
        $pdf->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($pdf);

        $agence = $this->entityManager->getRepository(Agence::class)->findBy(['id' => $adherent->getAgence()]);

        $image = $request->server->filter('SYMFONY_DEFAULT_ROUTE_URL') . 'uploads/agence/agence' . $agence[0]->getId() . '/picture/'. $agence[0]->getLinkPicture();

        $html = $this->renderView('file/pdfTestimony.html.twig', [
            'adherent' => $adherent,
            'image' => $image
        ]);

        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        $slug = $slugger->slug("temoignage-" . strtolower($adherent->getLastname()) . '-' . strtolower($adherent->getFirstname()));

        // Output the generated PDF to Browser (force download)
        return $dompdf->stream($slug , [
            'Attachment' => false
        ]);

    }

    //Route pour exporter les adhérents
    #[
        Route('/adherent/export', name: 'adherent_export'),
        IsGranted('ROLE_USER')
    ]
    public function adherentExportCsv(Request $request): Response
    {
        $userAgences = $this->getUser()->getAgence();

        foreach ($userAgences as $userAgence){
            $adherents = $userAgence->getAdherents();
            foreach ($adherents as $adherent){
                if($adherent->getGenre()->getName() === 'Féminin'){
                    $meets[] = $this->entityManager->getRepository(Meet::class)->findBy(['adherent_woman' => $adherent->getId()]);
                } else {
                    $meets[] = $this->entityManager->getRepository(Meet::class)->findBy(['adherent_man' => $adherent->getId()]);
                }
                $adherentcsv[] = $adherent;
            }
        }
        $csv = $this->renderView('file/template.csv.twig', [
                'adherents' => $adherentcsv,
                'meets' => $meets
        ]);

        $response = new Response($csv);

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            sprintf('file_%s.csv', time()));
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', $disposition);

        return $response;

    }
}
