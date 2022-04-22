<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/produit", name="produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/affiche", name="_affiche")
     */
    public function afficheAction(EntityManagerInterface $em): Response
    {
        $produitsRepository = $em->getRepository('App:Produit');
        $userRepository = $em->getRepository('App:Utilisateur');

        $produits = $produitsRepository->findAll();

        $user = $userRepository->find($this->getParameter('me'));

        if($user != null){
            $isAdmin = $user->getIsAdmin();
        }

        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
            'produits' => $produits ,
            'isAdmin' => $isAdmin,
        ]);
    }
    }
