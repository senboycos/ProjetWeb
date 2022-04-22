<?php

namespace App\Controller;

use App\Entity\PanierProduit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/panier", name="panier")
 */
class PanierController extends AbstractController
{
    /**
     * @Route("/commande", name="_commande")
     */
    public function commandeAction(EntityManagerInterface $em,Request $request): Response
    {
        $userRepository = $em->getRepository('App:Utilisateur');
        $produitRepository = $em->getRepository('App:Produit');

        $panierRepository = $em->getRepository('App:Panier');
        $panierproduitReposiriry = $em->getRepository('App:PanierProduit');
        $user = $userRepository->find($this->getParameter('me'));
        if($request->isMethod('POST')){
            foreach($_POST['selectedQuantite'] as $key => $value ){
                if($value != 0){
                    $produit = $produitRepository->find($key);
                    $produit->setQuantite($produit->getQuantite() - $value);
                    $ancienPanier = ($panierRepository->findBy(
                        ['client' => $user]
                    ));
                   // var_dump($ancienPanier);

                    if($ancienPanier != null){
                        $ancienPanier[0]->setQuantite(($ancienPanier[0]->getQuantite()) + $value);
                        $em->persist($ancienPanier[0]);
                        $panierproduit = new PanierProduit();
                        $panierproduit->setProduit($produit);
                        $panierproduit->setPanier($ancienPanier[0]);
                        $panierproduit->setQuantite($value);
                        $em->persist($panierproduit);
                    }
                }
            }
            $em->flush();
        }
        return $this->redirectToRoute('produit_affiche');
    }
    /**
     * @Route("/affiche", name="_affiche")
     */
    public function affichePanierAction(EntityManagerInterface $em,Request $request): Response
    {

        $userRepository = $em->getRepository('App:Utilisateur');
        $panierRepository = $em->getRepository('App:Panier');
        $panierproduitRepository = $em->getRepository('App:PanierProduit');
        $user = $userRepository->find($this->getParameter('me'));

        if($user != null){
            $isAdmin = $user->getIsAdmin();
        }
        $panier = $panierRepository->findBy(
            ['client' => $user]
        );
        $panierproduits =$panierproduitRepository->findBy(['panier' => $panier[0]]);
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'panierproduits' => $panierproduits ,
            'isAdmin' => $isAdmin,
        ]);
    }
    /**
     * @Route("/supprimerProduit/{idPanierproduit}", name="supprimer_element")
     */
    public function supprimeElementAction(EntityManagerInterface $em,Request $request,$idPanierproduit): Response
    {
        $userRepository = $em->getRepository('App:Utilisateur');
        $panierRepository = $em->getRepository('App:Panier');
        $produitRepository = $em->getRepository('App:Produit');
        $panierproduitRepository = $em->getRepository('App:PanierProduit');
        $user = $userRepository->find($this->getParameter('me'));
        $panierproduit = $panierproduitRepository->find($idPanierproduit);
        $produit=$panierproduit->getProduit();
        $produit->setQuantite($produit->getQuantite() + $panierproduit->getQuantite());
        $em->remove($panierproduit);
        $em->persist($produit);
        $em->flush();

        return $this->redirectToRoute('produit_affiche');
    }
    /**
     * @Route("/supprimerAllPanier", name="_supprimer_all")
     */
    public function supprimeAllAction(EntityManagerInterface $em,Request $request): Response
    {
        $userRepository = $em->getRepository('App:Utilisateur');
        $panierRepository = $em->getRepository('App:Panier');
        $panierproduitRepository = $em->getRepository('App:PanierProduit');
        $user = $userRepository->find($this->getParameter('me'));

        $paniers = $panierRepository->findBy(
            ['client'=>$user]
        );
        $panierproduits=$panierproduitRepository->findBy(
            ['panier'=>$paniers[0]]
        );
        foreach ($panierproduits as $panierproduit){
            $produit=$panierproduit->getProduit();
            $produit->setQuantite($produit->getQuantite() + $panierproduit->getQuantite());
            $em->remove($panierproduit);
            $em->persist($produit);
        }
        $em->flush();

        return $this->redirectToRoute('produit_affiche');
    }
    /**
     * @Route("/validerPanier", name="_valider")
     */
    public function validerPanierAction(EntityManagerInterface $em,Request $request): Response
    {
        $userRepository = $em->getRepository('App:Utilisateur');
        $panierRepository = $em->getRepository('App:Panier');
        $panierproduitRepository = $em->getRepository('App:PanierProduit');
        $user = $userRepository->find($this->getParameter('me'));

        $paniers = $panierRepository->findBy(
            ['client'=>$user]
        );
        $panierproduits=$panierproduitRepository->findBy(
            ['panier'=>$paniers[0]]
        );
        foreach ($panierproduits as $panierproduit){
            $em->remove($panierproduit);
        }
        $em->flush();

        return $this->redirectToRoute('produit_affiche');
    }

}
