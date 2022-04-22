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
    /**
     * @Route("/ajouter", name="_ajouter")
     */
    public function ajouterProduitAction(EntityManagerInterface $em,Request $request): Response
    {
        $userRepository = $em->getRepository('App:Utilisateur');

        $user = $userRepository->find($this->getParameter('me'));

        $isAdmin = false ;

        if($user !== null){
            $isAdmin = $user->getIsAdmin();
            if($isAdmin != true){
                return $this->redirectToRoute('login');
            }
        }

        $produit = new Produit();

        $form = $this->createForm(ProduitType::class, $produit);
        $form->add('send', SubmitType::class, ['label' => 'Ajout Produit']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($produit);
            $em->flush();
            $this->addFlash('info', 'ajout ok');
            return $this->redirectToRoute('produit_affiche');
        }

        if ($form->isSubmitted())
            $this->addFlash('info', 'ajout erreur');

        $args = array('myform' => $form->createView(), 'isAdmin' => $isAdmin);
        return $this->render('produit/ajout.html.twig', $args);
    }
}
