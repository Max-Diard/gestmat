<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Entity\AdherentOption;
use App\Entity\Meet;
use App\Form\AdherentType;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\FormError;

class AdherentController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager)
    {}

    //Liste de tous les adhérents
    #[
        Route('/adherent', name: 'adherent_all'),
        IsGranted('ROLE_USER')
    ]
    public function index(Request $request,): Response
    {
        $agences = $this->getUser()->getAgence();

        // On récupére les status meet pour ajouter dans le form de la modal
        $options = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'status_meet']);
        $actions = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'action_meet']);

        //Si l'utilisateur connecté à plus d'une agence, on itére dessus
        if (count($agences) > 0){
            for($i = 0, $iMax = count($agences); $i < $iMax; $i++){
                $agence = $agences[$i]->getId();
                
                $womenAdherent[] = $this->entityManager->getRepository(Adherent::class)->findByGenreAgences('Féminin', $agence);
                $menAdherent[] = $this->entityManager->getRepository(Adherent::class)->findByGenreAgences('Masculin', $agence);

                $fullAdherent = [
                    $i => $womenAdherent,
                    $i + 1 => $menAdherent
                ];


                    // DROIT AGENCE
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
                            if(!empty($haveMeet)){
                                $meeting[] = $adherent->getId();
                            }
                        }
                    }
                }
            }
//        dd($womenAdherent);

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
            if(in_array($agenceAdherent->getId(), $agenceIdUser, true)){
                $trueAgence = true;
            } else {
                $trueAgence = false;
            }

            $form = $this->createForm(AdherentType::class, $adherent, [
                'agences' => $userAgence
            ]);

            // Gestion de l'affichage du numéro de téléphone
            if ($form->get('phone_mobile')->getData()){
                $form->get('phone_mobile')->setData($this->replaceTel($adherent->getPhoneMobile()));
            }
            if ($form->get('phone_home')->getData()){
                $form->get('phone_home')->setData($this->replaceTel($adherent->getPhoneHome()));
            }
            if ($form->get('phone_work')->getData()){
                $form->get('phone_work')->setData($this->replaceTel($adherent->getPhoneWork()));
            }

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
            $meets = $this->entityManager->getRepository(Meet::class)->findBy([$genre => $adherent->getId()], ['id' => 'desc']);

            if(empty($meets)){
                if($adherent->getStatusMeet()->getName() !== 'En attente'){
                    $status = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'status_meet']);
                    foreach($status as $stat){
                        if($stat->getName() === "Disponible" ){
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

            // On vérifie le formulaire
            if($form->isSubmitted()){
                // On vérifie s'il n'y a pas de virgule
                $size = $form->get('size')->getData();
                $sizeFloat = (float)$size;
                if($sizeFloat > 3){
                    $form->get('size')->addError(new FormError('La valeur pour la "Taille" n\'est pas valide'));
                }

                if(str_contains($size, ',')) {
                    dd($size);
                    $size = str_replace(',','.', $size);
                }
                // On vérifie si le champ de la présentation de l'adhérent est remplie
                if($form->get('announcement_presentation')->getData() === ''){
                    $form->get('announcement_presentation')->addError(new FormError('Merci de bien vouloir remplir la présentation de l\'adhérent !'));
                }
            }

            // Envoi du formulaire
            if($form->isSubmitted() && $form->isValid()){
                $adherent = $form->getData();

                $adherent->setSize($size);

                // Récupération et changement du format du téléphone
                if(strlen($form->get('phone_mobile')->getData()) > 10){
                    $tel = str_replace(" ", "", $form->get('phone_mobile')->getData());
                    $adherent->setPhoneMobile($tel);
                }
                if(strlen($form->get('phone_home')->getData()) > 10){
                    $tel = str_replace(" ", "", $form->get('phone_home')->getData());
                    $adherent->setPhoneHome($tel);
                }
                if(strlen($form->get('phone_work')->getData()) > 10){
                    $tel = str_replace(" ", "", $form->get('phone_work')->getData());
                    $adherent->setPhoneWork($tel);
                }

                // Gestion des fichiers
                $fileInfo = $form->get('link_information')->getData();

                // Si une image est envoyé alors on ajoute l'information en BDD
                if($fileInfo){
                    // Si l'adhérent à déjà une image on supprime cette dernière du dossier 'public'
                    $this->moveLinkFile($fileInfo, 'information', $adherent->getId(), $adherent, $linkInfo);
                }
                // On récupére le nom de l'image déjà existant et on lui renvoi
                else {
                    $adherent->setLinkInformation($linkInfo);
                }

                $fileContract = $form->get('link_contract')->getData();

                // Si une image est envoyé alors on ajoute l'information en BDD
                if($fileContract){
                    // Si l'adhérent à déjà une image on supprime cette dernière du dossier 'public'
                    $this->moveLinkFile($fileContract, 'contract', $adherent->getId(), $adherent, $linkContract);
                }
                // On récupére le nom de l'image déjà existant et on lui renvoi
                else {
                    $adherent->setLinkContract($linkContract);
                }

                $fileAnnouncement = $form->get('link_picture_announcement')->getData();

                // Si une image est envoyé alors on ajoute l'information en BDD
                if ($fileAnnouncement){
                    // Si l'adhérent à déjà une image on supprime cette dernière du dossier 'public'
                    $this->moveLinkFile($fileAnnouncement, 'announcement', $adherent->getId(), $adherent,$linkAnnouncement);
                }
                // On récupére le nom de l'image déjà existant et on lui renvoi
                 else {
                    $adherent->setLinkPictureAnnouncement($linkAnnouncement);
                }

                $filePic = $form->get('link_picture')->getData();

                // Si une image est envoyé alors on ajoute l'information en BDD
                if ($filePic){
                    // Si l'adhérent à déjà une image on supprime cette dernière du dossier 'public'
                    $this->moveLinkFile($filePic, 'picture', $adherent->getId(), $adherent, $linkPic);
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

            if ($lastAdherent !== ''){
                $lastAdherent++;
            } else{
                $lastAdherent = 1;
            }

            $adherent = new Adherent();

            $form = $this->createForm(AdherentType::class, $adherent, [
                'agences' => $agences
            ]);


            $form->handleRequest($request);

            // On vérifie le formulaire
            if($form->isSubmitted()){
                // On vérifie s'il n'y a pas de virgule
                $size = $form->get('size')->getData();
                $sizeFloat = (float)$size;

                if($sizeFloat > 3 || $sizeFloat < 1){
                    $form->get('size')->addError(new FormError('La valeur pour la "Taille" n\'est pas valide'));
                }

                if(str_contains($size, ',')) {
                    $size = str_replace(',','.', $size);
                }
                // On vérifie si le champ de la présentation de l'adhérent est remplie
                if($form->get('announcement_presentation')->getData() === ''){
                    $form->get('announcement_presentation')->addError(new FormError('Merci de bien vouloir remplir la présentation de l\'adhérent !'));
                }

            }

            if($form->isSubmitted() && $form->isValid()){
                $adherent = $form->getData();

                $adherent->setSize($size);

                // Récupération et changement du format du téléphone
                if(strlen($form->get('phone_mobile')->getData()) > 10){
                    $tel = str_replace(" ", "", $form->get('phone_mobile')->getData());
                    $adherent->setPhoneMobile($tel);
                }
                if(strlen($form->get('phone_home')->getData()) > 10){
                    $tel = str_replace(" ", "", $form->get('phone_home')->getData());
                    $adherent->setPhoneHome($tel);
                }
                if(strlen($form->get('phone_work')->getData()) > 10){
                    $tel = str_replace(" ", "", $form->get('phone_work')->getData());
                    $adherent->setPhoneWork($tel);
                }

                $adherent->setActive(true);

                $fileInfo = $form->get('link_information')->getData();
                // Si une image est envoyé alors on ajoute l'information en BDD
                if($fileInfo){
                    $this->moveLinkFile($fileInfo, 'information' ,$lastAdherent, $adherent);
                }

                $fileContract = $form->get('link_contract')->getData();
                // Si une image est envoyé alors on ajoute l'information en BDD
                if($fileContract){
                    $this->moveLinkFile($fileContract, 'contract', $lastAdherent, $adherent,);
                }

                $fileAnnouncement = $form->get('link_picture_announcement')->getData();
                // Si une image est envoyé alors on ajoute l'information en BDD
                if($fileAnnouncement){
                    $this->moveLinkFile($fileAnnouncement, 'announcement', $lastAdherent, $adherent);
                }

                $filePic = $form->get('link_picture')->getData();
                // Si une image est envoyé alors on ajoute l'information en BDD
                if($filePic){
                    $this->moveLinkFile($filePic, 'picture', $lastAdherent, $adherent);
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
        Route('/adherent/profil/{id}/meet/all', name: 'adherent_profil_meet_all'),
        IsGranted('ROLE_USER')
    ]
    public function adherentMeetAll(Adherent $adherent): Response
    {
        if(count($this->getUser()->getAgence()) > 0) {

            if($adherent->getGenre()->getName() == 'Féminin'){
                $meets = $this->entityManager->getRepository(Meet::class)->findBy(['adherent_woman' => $adherent->getId()], ['id' => 'desc']);
            } else {
                $meets = $this->entityManager->getRepository(Meet::class)->findBy(['adherent_man' => $adherent->getId()], ['id' => 'desc']);
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
        Route('/adherent/profil/{id}/testimony', name: 'adherent_testimony'),
        IsGranted('ROLE_USER')
    ]
    public function testimonyAdherent(Adherent $adherent, Request $request, SluggerInterface $slugger)
    {
        $pdf = new Options();
        $pdf->set('defaultFont', 'Arial');
        $pdf->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($pdf);

        $agence = $adherent->getAgence();

//        $image = $request->getSchemeAndHttpHost() . '/uploads/agence/agence' . $agence->getId() . '/picture/'. $agence->getLinkPicture();

        $image = $this->getParameter('agence_directory') . 'agence' . $agence->getId() . '/picture/'. $agence->getLinkPicture();
        $type = pathinfo($image, PATHINFO_EXTENSION);
        $data = file_get_contents($image);
        $imageBase64 = 'data:/image' . $type . ';base64,' . base64_encode($data);

        $html = $this->renderView('file/pdfTestimony.html.twig', [
            'adherent' => $adherent,
            'image' => $imageBase64
        ]);

        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        $slug = $slugger->slug("temoignage-" . strtolower($adherent->getLastname()) . '-' . strtolower($adherent->getFirstname()));

        $output = $dompdf->output();

        if(!is_dir($this->getParameter('testimony_directory'))){
            if (!mkdir($concurrentDirectory = $this->getParameter('testimony_directory')) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }

        $location = $this->getParameter('testimony_directory') . $slug . '.pdf';

        file_put_contents($location , $output);

        // Output the generated PDF to Browser (force download)

        return new BinaryFileResponse(($this->getParameter('testimony_directory') . $slug . '.pdf'));

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

    #[
        Route('/adherent/pdf/test/{id}', name: 'test_pdf'),
        IsGranted('ROLE_USER')
    ]
    public function adherenttest(Adherent $adherent, Request $request, MailerInterface $mailer, SluggerInterface $slugger): Response
    {
        $pdf = new Options();
        $pdf->set('defaultFont', 'Arial');
        $pdf->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($pdf);

        $agence = $adherent->getAgence();

        $image = $request->getSchemeAndHttpHost() . '/uploads/agence/agence' . $agence->getId() . '/picture/'. $agence->getLinkPicture();
        $image2 = $request->getHttpHost() . '/uploads/agence/agence' . $agence->getId() . '/picture/'. $agence->getLinkPicture();
        $image3 = $this->getParameter('agence_directory') . 'agence' . $agence->getId() . '/picture/'. $agence->getLinkPicture();
        $type = pathinfo($image3, PATHINFO_EXTENSION);
        $data = file_get_contents($image3);
        $base64 = 'data:/image' . $type . ';base64,' . base64_encode($data);
        $image4 = getcwd(). '/uploads/agence/agence' . $agence->getId() .' /picture/'. $agence->getLinkPicture();
        $image5 = "http://127.0.0.1:8000/uploads/agence/agence23/picture/7f48f4a8af4007223ba83d8d441be4e2.jpg";

        $html = $this->renderView('file/pdfTest.html.twig', [
            'image1' => $image,
            'image2' => $image2,
            'image3' => $base64,
            'image4' => $image4,
            'image5' => $image5
        ]);

        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        $slug = $slugger->slug("temoignage-" . strtolower($adherent->getLastname()) . '-' . strtolower($adherent->getFirstname()));

        $output = $dompdf->output();

        if(!is_dir($this->getParameter('testimony_directory'))){
            if (!mkdir($concurrentDirectory = $this->getParameter('testimony_directory')) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }

        $location = $this->getParameter('testimony_directory') . $slug . '.pdf';

        file_put_contents($location , $output);

        // Output the generated PDF to Browser (force download)

        return new BinaryFileResponse(($this->getParameter('testimony_directory') . $slug . '.pdf'));

    }

    private function replaceTel(string $tel): string
    {
        if(str_contains($tel,'+33')){
            $tel = str_replace(["+33 ", "+33"], "0", $tel);
        }
        return wordwrap($tel, 2 , ' ', 1);
    }

    private function moveLinkFile($file, $typeFile, $adherentId, $adherent, $fileActually = null): void
    {
        if($fileActually !== null){
            unlink($this->getParameter('adherent_directory') . 'adherent' . $adherentId . '/'. $typeFile . '/' . $fileActually);
        }

        $fileExt = $file->guessExtension();
        $fileName = md5(uniqid('', true)) . '.' . $fileExt;
        $file->move($this->getParameter('adherent_directory') . 'adherent' . $adherentId . '/'. $typeFile . '/', $fileName);

        if($typeFile === 'information'){
            $adherent->setLinkInformation($fileName);
        }
        if($typeFile === 'contract'){
            $adherent->setLinkContract($fileName);
        }
        if($typeFile === 'announcement'){
            $adherent->setLinkPictureAnnouncement($fileName);
        }
        if($typeFile === 'picture'){
            $adherent->setLinkPicture($fileName);
        }
    }
}
