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
        $dateChange = $dateStart;

        for($i = 0; $i < $dateIncrement; $i++){
            $meets[] = $this->entityManager->getRepository(Meet::class)->findBy(['startedAt' => $dateChange]);
            $dateChange = $dateChange->add(new DateInterval('P1D'));
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

            $adherentPaper = [];
            $adherentEmail = [];

            if (array_key_exists('paper', $this->adherentPreference($trueMeet, $agenceUser))){
                $adherentPaper = $this->adherentPreference($trueMeet, $agenceUser)['paper'];
            }
            if (array_key_exists('email', $this->adherentPreference($trueMeet, $agenceUser))){
                $adherentEmail = $this->adherentPreference($trueMeet, $agenceUser)['email'];
            }
            if (array_key_exists('trueAgence', $this->adherentPreference($trueMeet, $agenceUser))){
                $trueAgence = $this->adherentPreference($trueMeet, $agenceUser)['trueAgence'];
            }
        } else {
            $trueMeet = [];
            $trueAgence = [];
        }

        return $this->render('meet/seeAllPdf.html.twig', [
            'meets' => $trueMeet,
            'trueAgence' => $trueAgence,
            'adherentPapers' => $adherentPaper,
            'adherentEmails' => $adherentEmail
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
        $agenceUser = $this->getUser()->getAgence();

        $dateIncrement = date_diff($dateStart, $dateEnd)->days;

        for($i = 0; $i < $dateIncrement; $i++){
            $meets[] = $this->entityManager->getRepository(Meet::class)->findBy(['startedAt' => $dateStart]);

            $dateStart = $dateStart->add(new DateInterval('P1D'));
        }

        $adherentPaper = [];
        $adherentEmail = [];

        if (!empty($meets)){
            $trueMeet = $this->trueMeet($meets);
            foreach ($meets as $ms) {
                if (array_key_exists('paper', $this->adherentPreference($ms, $agenceUser))){
                    $adherentPaper = $this->adherentPreference($ms, $agenceUser)['paper'];
                }
                if (array_key_exists('email', $this->adherentPreference($ms, $agenceUser))){
                    $adherentEmail = $this->adherentPreference($ms, $agenceUser)['email'];
                }
                if (array_key_exists('trueAgence', $this->adherentPreference($ms, $agenceUser))){
                    $trueAgence = $this->adherentPreference($ms, $agenceUser)['trueAgence'];
                }
            }
        } else{
            $trueMeet = [];
            $trueAgence = [];
        }

        return $this->render('meet/seeAllPdf.html.twig', [
            'meets' => $trueMeet,
            'trueAgence' => $trueAgence,
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
        $agenceUser = $this->getUser()->getAgence();

        // On regarde d'où viens l'utilisateur
        if(substr($lien, -10) === '/send_all/'){
            if(count($agenceUser) > 0){
                $meets = $this->meets($agenceUser);
                $trueMeet = $this->trueMeet($meets);

                $trueAgence = [];
                foreach ($trueMeet as $meet) {
                    $adherentPaper = $this->adherentPaper($meet, $agenceUser);
                    if(!empty($adherentPaper)){
                        $html .= $this->renderHtml($adherentPaper, $meet, $request, $date);
                    }
                }
            } else {
                $trueMeet = [];
                $trueAgence = [];
            }
        }
        else {
            $agenceUser = $this->getUser()->getAgence();

            $dateLinkStart = substr($lien, -21, -11);
            $dateLinkEnd = substr($lien, -10);

            $dateImmuStart = new DateTimeImmutable($dateLinkStart);
            $dateImmuEnd = new DateTimeImmutable($dateLinkEnd);

            $dateIncrement = date_diff($dateImmuStart, $dateImmuEnd)->days;

            if(count($agenceUser) > 0){
                for($i = 0; $i < $dateIncrement; $i++){
                    $meets[] = $this->entityManager->getRepository(Meet::class)->findBy(['startedAt' => $dateImmuStart]);

                    $dateImmuStart = $dateImmuStart->add(new DateInterval('P1D'));
                }

                if (!empty($meets)){
                    $trueMeet = $this->trueMeet($meets);
                    foreach ($trueMeet as $meet){
                        $adherentPaper = $this->adherentPaper($meet, $agenceUser);

                        if(!empty($adherentPaper)){
                            $html .= $this->renderHtml($adherentPaper, $meet, $request, $date);
                        }
                    }
                } else {
                    $trueMeet = [];
                    $trueAgence = [];
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

    private function adherentPaper($meet, $agenceUser)
    {
        $adherentPaper = [];
        foreach ($agenceUser as $agence) {
            if ($meet->getAdherentWoman()->getAgence() === $agence) {
                if ($meet->getAdherentWoman()->getPreferenceContact()->getName() === 'Courrier') {
                    $adherentPaper[] = $meet->getAdherentWoman();
                }
            }
            if ($meet->getAdherentMan()->getAgence() === $agence) {
                if ($meet->getAdherentMan()->getPreferenceContact()->getName() === 'Courrier') {
                    $adherentPaper[] = $meet->getAdherentMan();
                }
            }
        }
        return $adherentPaper;
    }

    private function renderHtml($adherentPaper, $meet, $request, $date)
    {
        $html= '';
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
        return $html;
    }

    private function adherentPreference($meets, $agenceUser)
    {
        $adherent = [];
        foreach ($meets as $meet){
            foreach ($agenceUser as $agence){
                if ($meet->getAdherentWoman()->getAgence() === $agence){
                    $adherent['trueAgence'][] = $meet->getAdherentWoman();
                    if($meet->getAdherentWoman()->getPreferenceContact()->getName() === 'Courrier'){
                        $adherent['paper'][] = $meet->getAdherentWoman();
                    } else {
                        $adherent['email'][] = $meet->getAdherentWoman();
                    }
                }
                if($meet->getAdherentMan()->getAgence() === $agence){
                    $adherent['trueAgence'][] = $meet->getAdherentMan();
                    if($meet->getAdherentMan()->getPreferenceContact()->getName() === 'Courrier'){
                        $adherent['paper'][] = $meet->getAdherentMan();
                    } else {
                        $adherent['email'][] = $meet->getAdherentMan();
                    }
                }
            }
        }
        return $adherent;
    }

}
