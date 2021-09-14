<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Entity\User;
use App\Form\AgenceType;
use App\Form\ChangeAgenceType;
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

    #[
        Route('/agence/{id}', name: 'agence_single'),
        IsGranted('ROLE_USER'),
        // Security('agence.getUsers() == user.getAgence()')
    ]
    public function index(Agence $agence): Response
    {
        // Gérer le fait que si l'agence n'est pas lier à l'utilisateur il ne peux pas y accéder

        $user = $this->getUser();
        // $user = $user->getAgence();
        for ($i = 0; $i < count($agence->getUsers()); $i++){
            if($user == $agence->getUsers()[$i]){
                return $this->render('agence/singleAgence.html.twig', [
                    'agence' => $agence,
                    // 'user' => $user
                ]);
            }
        }
        // dd('marche pas ');
        // dd($user, $agence);
        // dd($user->getAgence());
        // $sql = $this->entityManager->getRepository(User::class)->AgencyAccessUser($user, $agence);
        // dd($sql);
        // $agence = $agence->getUser();
        // dd($agence->getUsers());
        $this->denyAccessUnlessGranted('agence_edit', $agence);

        return $this->render('agence/singleAgence.html.twig', [
            'agence' => $agence,
            // 'user' => $user
        ]);

        
    }

    #[
        Route('/agence/{id}/modify', name: 'agence_modify')
    ]
    public function modifyAgence(Agence $agence, Request $request): Response
    {
        $linkPic = $agence->getLinkPicture();

        $form = $this->createForm(ChangeAgenceType::class, $agence);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $agence = $form->getData();

            $picture = $form->get('link_picture')->getData();
            // Si une image est envoyé alors on ajoute l'information en BDD
            if($picture){
                if ($linkPic){
                    unlink($this->getParameter('agence_directory') . 'agence' . $agence->getId() . '/picture/' . $linkPic);
                }
                $pictureExt = $picture->guessExtension();
                $pictureName = md5(uniqid()) . '.' . $pictureExt;
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
            'form' => $form->createView()
        ]);
    }
}
