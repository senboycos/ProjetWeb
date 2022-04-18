<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AcceuilController extends AbstractController
{

    /**
     * @Route("/", name="acceuil")
     */
    public function indexAction(ManagerRegistry $doctrine): Response
    {
        $userExists = false;
        $isAdmin = false;
        $idUser = $this->getParameter('me');
        $em = $doctrine->getManager();
        $userRepository = $em->getRepository('App:Utilisateur');

        $currentUser = $userRepository->find($idUser);

        // dump($currentUser);
        if($currentUser != null){
            $isAdmin = $currentUser->getIsAdmin();
            $userExists = true;
        }

        if($userExists)
        {
            return $this->render('accueil\index.html.twig', [
                'user' => $currentUser,
                'isAdmin' => $isAdmin,
            ]);
        } else
        {
            return $this->redirectToRoute("login");
        }
    }
}
