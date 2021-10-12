<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Entity\AdherentOption;
use App\Entity\Agence;
use App\Entity\Meet;
use App\Entity\User;
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
use Symfony\Config\Framework\RouterConfig;

class MeetController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    //Page pour voir tous les rencontres
    #[
        Route('/meet', name: 'meet_all'),
        IsGranted('ROLE_USER')
    ]
    public function index(): Response
    {
        $agenceUser = $this->getUser()->getAgence();

        $options = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'status_meet']);

        if(count($agenceUser) > 0){
            foreach($agenceUser as $agence){
                $adherents[] = $this->entityManager->getRepository(Adherent::class)->findBy(['agence' => $agence->getId()]);
                foreach ($adherents as $allAdherent){
                    foreach ($allAdherent as $adherent){
                        if ($adherent->getGenre()->getName() === 'Féminin'){
                            $meets[] = $this->entityManager->getRepository(Meet::class)->findBy(['adherent_woman' => $adherent->getId()]);
                        } else {
                            $meets[] = $this->entityManager->getRepository(Meet::class)->findBy(['adherent_man' => $adherent->getId()]);
                        }
                    }
                }
            }
        }
        $trueMeet = [];
        foreach ($meets as $meet){
            if (!empty($meet)){
                foreach($meet as $m){
                    $trueMeet += [
                        $m->getId() => $m
                    ];
                }
            }
        }

        return $this->render('meet/index.html.twig', [
            'meets' => $trueMeet,
            'options' => $options
        ]);
    }

    //Page pour voir tous les rencontres avec la date
    #[
        Route('/meet/search/{date}', name: 'meet_date'),
        ParamConverter('date' , \DateTimeImmutable::class),
        IsGranted('ROLE_USER')
    ]
    public function searchMeet(DateTimeImmutable $date): Response
    {
        $meets = $this->entityManager->getRepository(Meet::class)->findBy(['startedAt' => $date]);

        $options = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'status_meet']);


        return $this->render('meet/index.html.twig', [
            'meets' => $meets,
            'dateUrl' => $date,
            'options' => $options
        ]);
    }

    //Route pour créer une rencontre
    #[
        Route('/meet/new/{woman}-{man}-{date}', name: 'meet_new'),
        IsGranted('ROLE_USER')
    ]
    public function newMeet(Adherent $woman, Adherent $man, DateTimeImmutable $date, UrlGeneratorInterface $router): Response
    {
//        $meet = new Meet();
//
//        $meet->setAdherentWoman($woman);
//        $meet->setAdherentMan($man);
//        $meet->setStartedAt($date);
//
//        $today = new DateTimeImmutable("now");
//        if ($date > $today){
//            $disponibility = $this->entityManager->getRepository(AdherentOption::class)->findBy(['type' => 'status_meet', 'name' => 'En rencontre']);
//            $woman->setStatusMeet($disponibility[0]);
//            $man->setStatusMeet($disponibility[0]);
//
//            $this->entityManager->persist($woman);
//            $this->entityManager->persist($man);
//            $this->entityManager->flush();
//        }
//
//        $this->entityManager->persist($meet);
//        $this->entityManager->flush();
//
//        $hashwoman = '?woman=';
////        dd($hashwoman);
////         $this->redirectToRoute('adherent_all');
//        $url = $this->generateUrl('adherent_all'). $hashwoman. $woman->getId() . '&men=' . $man->getId();
////        dd($url);
//        return $this->redirectToRoute($url);
//        dd($router->generate('adherent_all', [$hashwoman => $woman->getId()]));

    }

    //Page pour voir l'annonce du pdf
    #[
        Route('/meet/pdf/{adherent}-{meet}', name: 'adherent_single_pdf'),
        IsGranted('ROLE_USER')
    ]
    public function seePdfAdherent(Adherent $adherent, Adherent $meet, Request $request)
    {
        $date = new DateTimeImmutable('now');

        $pdf = new Options();
        $pdf->set('defaultFont', 'Arial');
        $pdf->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($pdf);

        $agence = $this->entityManager->getRepository(Agence::class)->findBy(['id' => $adherent->getAgence()]);

        $image = $request->server->filter('SYMFONY_DEFAULT_ROUTE_URL') . 'uploads/agence/agence' . $agence[0]->getId() . '/picture/'. $agence[0]->getLinkPicture();

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

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("rencontre-" . strtolower($adherent->getLastname()) .'-'. strtolower($adherent->getFirstname()), array('Attachment' => true));

//        return new Response('', 200, [
//            'Content-Type' => 'application/pdf',
//        ]);
    }

    //Page pour envoyer les demandes de témoignages vis à vis de leur préférence avec une date précise
    #[
        Route('/meet/send/{date}', name: 'meet_send_date'),
        ParamConverter('date' , \DateTimeImmutable::class),
        IsGranted('ROLE_USER')
    ]
    public function sendResultMeetWithDate(DateTimeImmutable $date): Response
    {
        $meets = $this->entityManager->getRepository(Meet::class)->findBy(['startedAt' => $date]);
        if (!empty($meets)){
            foreach ($meets as $meet){
                $adherentMans[] = $meet->getAdherentMan();
                $adherentWomans[] = $meet->getAdherentWoman();
            }
        }

        return $this->render('meet/seeAllPdf.html.twig', [
            'meets' => $meets,
        ]);
    }

    //Page pour envoyer les demandes de témoignages vis à vis de leur préférence
    #[
        Route('/meet/send_all/', name: 'meet_send'),
        IsGranted('ROLE_USER')
    ]
    public function sendResultsMeet(): Response
    {
        $agenceUser = $this->getUser()->getAgence();



        if(count($agenceUser) > 0){
            foreach($agenceUser as $agence){
                $adherents[] = $this->entityManager->getRepository(Adherent::class)->findBy(['agence' => $agence->getId()]);
                foreach ($adherents as $allAdherent){
                    foreach ($allAdherent as $adherent){
                        if ($adherent->getGenre()->getName() === 'Féminin'){
                            $meets[] = $this->entityManager->getRepository(Meet::class)->findBy(['adherent_woman' => $adherent->getId()]);
                        } else {
                            $meets[] = $this->entityManager->getRepository(Meet::class)->findBy(['adherent_man' => $adherent->getId()]);
                        }
                    }
                }
            }
        }
        $trueMeet = [];
        foreach ($meets as $meet){
            if (!empty($meet)){
                foreach($meet as $m){
                    $trueMeet += [
                        $m->getId() => $m
                    ];
                }
            }
        }

        return $this->render('meet/seeAllPdf.html.twig', [
            'meets' => $trueMeet,
        ]);
    }

//    //Route pour supprimer la rencontre
//    #[
//        Route('/meet/{id}/remove/', name: 'meet_remove'),
//        IsGranted('ROLE_USER')
//    ]
//    public function removeMeet(Meet $meet): Response
//    {
//        $this->entityManager->remove($meet);
//        $this->entityManager->flush();
//
//        $this->addFlash('successRemoveMeet', 'La rencontre à bien était supprimé !');
//
//        return $this->redirectToRoute('adherent_all');
//    }
}
