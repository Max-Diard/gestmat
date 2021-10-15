<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Entity\AdherentOption;
use App\Entity\Agence;
use App\Entity\Meet;
use App\Entity\User;
use Cassandra\Date;
use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Config\Framework\RouterConfig;

class MeetController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {}

    //Page pour voir tous les rencontres
    #[
        Route('/meet/search', name: 'meet_all'),
        IsGranted('ROLE_USER')
    ]
    public function index(): Response
    {
        $agenceUser = $this->getUser()->getAgence();

        $options = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'status_meet']);

        if(count($agenceUser) > 0){
            $meets = $this->meets($agenceUser);
            $trueMeet = $this->trueMeet($meets);
        } else {
            $trueMeet = [];
        }

        return $this->render('meet/index.html.twig', [
            'meets' => $trueMeet,
            'options' => $options,
        ]);
    }

    //Page pour voir tous les rencontres avec une date
    #[
        Route('/meet/search/{dateStart}/{dateEnd}', name: 'meet_date'),
        ParamConverter('dateStart' , \DateTimeImmutable::class),
        ParamConverter('dateEnd' , \DateTimeImmutable::class),
        IsGranted('ROLE_USER')
    ]
    public function searchMeet(DateTimeImmutable $dateStart, DateTimeImmutable $dateEnd): Response
    {
        $options = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'status_meet']);

        $dateIncrement = date_diff($dateStart, $dateEnd)->days;

        for($i = 0; $i < $dateIncrement; $i++){
            $meets[] = $this->entityManager->getRepository(Meet::class)->findBy(['startedAt' => $dateStart]);
            $dateStart = $dateStart->add(new DateInterval('P1D'));
        }
        if(!empty($meets)){
            $trueMeet = $this->trueMeet($meets);
        } else {
            $trueMeet = [];
        }

        return $this->render('meet/index.html.twig', [
            'meets' => $trueMeet,
            'dateUrlStart' => $dateStart,
            'dateUrlEnd' => $dateEnd,
            'options' => $options,
        ]);
    }

    //Page pour voir les pdf de rencontre vis à vis de leur préférence et avec une date précise
    #[
        Route('/meet/send/{dateStart}/{dateEnd}', name: 'meet_send_date'),
        ParamConverter('dateStart' , \DateTimeImmutable::class),
        ParamConverter('dateEnd' , \DateTimeImmutable::class),
        IsGranted('ROLE_USER')
    ]
    public function searchResultMeetWithDate(DateTimeImmutable $dateStart, DateTimeImmutable $dateEnd): Response
    {
        dd($dateStart, $dateEnd);

        $meets = $this->entityManager->getRepository(Meet::class)->findBy(['startedAt' => $date]);

        $adherentPaper = [];
        $adherentEmail = [];

        if (!empty($meets)){
            foreach ($meets as $meet) {
                $adherentMans[] = $meet->getAdherentMan();
                $adherentWomans[] = $meet->getAdherentWoman();
                if($meet->getAdherentWoman()->getPreferenceContact()->getName() == 'Courrier'){
                    $adherentPaper[] = $meet->getAdherentWoman();
                } else {
                    $adherentEmail[] = $meet->getAdherentWoman();
                }
                if($meet->getAdherentMan()->getPreferenceContact()->getName() == 'Courrier'){
                    $adherentPaper[] = $meet->getAdherentMan();
                } else {
                    $adherentEmail[] = $meet->getAdherentMan();
                }
            }
        }

        return $this->render('meet/seeAllPdf.html.twig', [
            'meets' => $meets,
            'adherentPapers' => $adherentPaper,
            'adherentEmails' => $adherentEmail
        ]);
    }

    //Page pour voir les pdfs de rencontres à tous les adhérents et vis à vis de leur préférence
    #[
        Route('/meet/send_all/', name: 'meet_send'),
        IsGranted('ROLE_USER')
    ]
    public function searchResultsMeet(): Response
    {
        $agenceUser = $this->getUser()->getAgence();

        if(count($agenceUser) > 0) {
            $meets = $this->meets($agenceUser);
            $trueMeet = $this->trueMeet($meets);
        } else {
            $trueMeet = [];
        }

        $adherentPaper = [];
        $adherentEmail = [];

        foreach ($trueMeet as $meet){
            if($meet->getAdherentWoman()->getPreferenceContact()->getName() == 'Courrier'){
                $adherentPaper[] = $meet->getAdherentWoman();
            } else {
                $adherentEmail[] = $meet->getAdherentWoman();
            }
            if($meet->getAdherentMan()->getPreferenceContact()->getName() == 'Courrier'){
                $adherentPaper[] = $meet->getAdherentMan();
            } else {
                $adherentEmail[] = $meet->getAdherentMan();
            }
        }

        return $this->render('meet/seeAllPdf.html.twig', [
            'meets' => $trueMeet,
            'adherentPapers' => $adherentPaper,
            'adherentEmails' => $adherentEmail
        ]);
    }

    // --------------------------- Function pour créer les pdf --------------------------- //
    //Page pour voir le pdf de rencontre de l'adhérent sélectionner
    #[
        Route('/meet/pdf/{adherent}-{meet}', name: 'adherent_single_pdf'),
        IsGranted('ROLE_USER')
    ]
    public function seePdfAdherent(Adherent $adherent, Adherent $meet, Request $request, SluggerInterface $slugger)
    {
        $date = new DateTimeImmutable('now');

        $pdf = new Options();
        $pdf->set('defaultFont', 'Arial');
        $pdf->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($pdf);

        $agence = $adherent->getAgence()->getLinkPicture();

        $image = $request->server->filter('SYMFONY_DEFAULT_ROUTE_URL') . 'uploads/agence/agence' . $adherent->getAgence()->getId() . '/picture/'. $agence;

        $html = $this->renderView('meet/pdfView.html.twig', [
            'adherent' => $adherent,
            'meet' => $meet,
            'date' => $date,
            'image' => $image
        ]);

        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        $output = $dompdf->output();
        $location = $this->getParameter('meet_directory') . $meet->getId() .  "/Rencontre-" . $adherent->getLastname() . '-' . $adherent->getFirstname() . '.pdf';

        if (!is_dir($this->getParameter('meet_directory'). $meet->getId() )) {
            mkdir($this->getParameter('meet_directory') . $meet->getId() .  "/", 0777, true);
        }
        file_put_contents($location, $output);

        $slug = $slugger->slug('rencontre-' . strtolower($adherent->getLastname()) .'-'. strtolower($adherent->getFirstname()));

        // Output the generated PDF to Browser (force download)
        $dompdf->stream($slug , array('Attachment' => true));
    }

    // Création des pdfs pour tous les adhérents qui ont une préférence de contact 'courrier'
    #[
        Route('/meet/send/paper', name: 'meet_search_paper'),
        IsGranted('ROLE_USER')
    ]
    public function sendAllMeetPaper(Request $request, SluggerInterface $slugger): Response
    {
        $lien = $request->server->get('HTTP_REFERER');

        $date = new DateTimeImmutable('now');

        $pdf = new Options();
        $pdf->set('defaultFont', 'Arial');
        $pdf->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($pdf);

        $html = '';

        if(substr($lien, -5) === '/meet'){
            $agenceUser = $this->getUser()->getAgence();

            if(count($agenceUser) > 0){
                $meets = $this->meets($agenceUser);
                $trueMeet = $this->trueMeet($meets);
                foreach ($trueMeet as $meet){
                    $adherentPaper = [];

                    if($meet->getAdherentWoman()->getPreferenceContact()->getName() == 'Courrier'){
                        $adherentPaper[] = $meet->getAdherentWoman();
                    }
                    if($meet->getAdherentMan()->getPreferenceContact()->getName() == 'Courrier'){
                        $adherentPaper[] = $meet->getAdherentMan();
                    }

                    if(!empty($adherentPaper)){

                        foreach ($adherentPaper as $adherentSend){
                            if($adherentSend->getGenre()->getName() === 'Féminin'){
                                $genre = $meet->getAdherentMan();
                            } else {
                                $genre = $meet->getAdherentWoman();
                            }

                            $agence = $adherentSend->getAgence()->getLinkPicture();

                            $image = $request->server->filter('SYMFONY_DEFAULT_ROUTE_URL') . 'uploads/agence/agence' . $adherentSend->getAgence()->getId() . '/picture/'. $agence;

                            $html .= $this->renderView('meet/pdfView.html.twig', [
                                'adherent' => $adherentSend,
                                'meet' => $genre,
                                'date' => $date,
                                'image' => $image
                            ]);
                        }
                    }
                }
            } else {
                $trueMeet = [];
            }
        }
        else {
            $dateLink = substr($lien, -10);
            $dateImmu = new DateTimeImmutable($dateLink);
            $meets = $this->entityManager->getRepository(Meet::class)->findBy(['startedAt' => $dateImmu]);

            if (!empty($meets)){
                foreach ($meets as $meet){
                    $adherentPaper = [];

                    if($meet->getAdherentWoman()->getPreferenceContact()->getName() == 'Courrier'){
                        $adherentPaper[] = $meet->getAdherentWoman();
                    }
                    if($meet->getAdherentMan()->getPreferenceContact()->getName() == 'Courrier'){
                        $adherentPaper[] = $meet->getAdherentMan();
                    }

                    if(!empty($adherentPaper)){

                        foreach ($adherentPaper as $adherentSend){
                            if($adherentSend->getGenre()->getName() === 'Féminin'){
                                $genre = $meet->getAdherentMan();
                            } else {
                                $genre = $meet->getAdherentWoman();
                            }

                            $agence = $adherentSend->getAgence()->getLinkPicture();

                            $image = $request->server->filter('SYMFONY_DEFAULT_ROUTE_URL') . 'uploads/agence/agence' . $adherentSend->getAgence()->getId() . '/picture/'. $agence;

                            $html .= $this->renderView('meet/pdfView.html.twig', [
                                'adherent' => $adherentSend,
                                'meet' => $genre,
                                'date' => $date,
                                'image' => $image
                            ]);
                        }
                    }
                }
            }
        }

        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        $date = Date('m.d.Y');

        $output = $dompdf->output();
        $location = $this->getParameter('meet_directory') .  "/" . $date . '.pdf';

        if (!is_dir($this->getParameter('meet_directory'). $date )) {
            mkdir($this->getParameter('meet_directory') . $date .  "/", 0777, true);
        }
        file_put_contents($location, $output);

        $slug = $slugger->slug('impression-du-' . $date);

        // Output the generated PDF to Browser (force download)
        $dompdf->stream($slug , array('Attachment' => true));
    }

    // --------------------------- Function private --------------------------- //
    private function meets($agenceUser)
    {
        foreach ($agenceUser as $agence) {
            $adherents[] = $this->entityManager->getRepository(Adherent::class)->findBy(['agence' => $agence->getId()]);
            foreach ($adherents as $allAdherent) {
                foreach ($allAdherent as $adherent) {
                    if ($adherent->getGenre()->getName() === 'Féminin') {
                        $meets[] = $this->entityManager->getRepository(Meet::class)->findBy(['adherent_woman' => $adherent->getId()]);
                    } else {
                        $meets[] = $this->entityManager->getRepository(Meet::class)->findBy(['adherent_man' => $adherent->getId()]);
                    }
                }
            }
        }
        return $meets;
    }

    private function trueMeet($meets)
    {
        $trueMeet = [];
        foreach ($meets as $meet) {
            if (!empty($meet)) {
                foreach ($meet as $m) {
                    $trueMeet += [
                        $m->getId() => $m
                    ];
                }
            }
        }
        return $trueMeet;
    }
}
