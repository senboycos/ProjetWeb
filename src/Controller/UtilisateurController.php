<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/utilisateur", name="utilisateur")
 */
class UtilisateurController extends AbstractController
{
    /**
     * @Route("/addUser",
     *     name="_addUser",
     *     )
     */
    public function addUserAction(EntityManagerInterface $em, Request $request): Response
    {
        $utilisateurRepository = $em->getRepository('App:Utilisateur');
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->add('send', SubmitType::class, ['label' => 'edit utilisateur']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $utilisateur->setIsadmin(false);
            $em->persist($utilisateur);
            $em->flush();
            $this->addFlash('info','edition utilsateur reussit');
            return $this->redirectToRoute('acceuil');
        }
        $args = array('myform' => $form->createView());
        return $this->render('utilisateur/addU.html.twig',$args);
    }
    /**
     * @Route("/addUserA",
     *     name="_addUserA",
     *     )
     */
    public function addUserAAction(EntityManagerInterface $em, Request $request): Response
    {
        $utilisateurRepository = $em->getRepository('App:Utilisateur');
        $user = $utilisateurRepository->find($this->getParameter('me'));
        if($user->getIsAdmin() == true){
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->add('send', SubmitType::class, ['label' => 'edit utilisateur']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $utilisateur->setIsadmin(true);
            $em->persist($utilisateur);
            $em->flush();
            $this->addFlash('info','edition utilsateur reussit');
            return $this->redirectToRoute('acceuil');
        }
        $args = array('myform' => $form->createView());
        return $this->render('utilisateur/addU.html.twig',$args);
        }
    }
    /**
     * @Route("/edit", name="edit_profil")
     */
    public function editProfil(EntityManagerInterface $em, Request $request): Response
    {
        $utilisateurRepository = $em->getRepository('App:Utilisateur');
        $user = $utilisateurRepository->find($this->getParameter('me'));

        $form = $this->createForm(UtilisateurType::class, $user);
        $form->add('send', SubmitType::class, ['label' => 'Modifier l\'utilisateur']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
          //  $user->setIsadmin(false);
            $em->persist($user);
            $em->flush();
            $this->addFlash('info', 'edition ok');
            return $this->redirectToRoute('acceuil');
        }
        if ($form->isSubmitted())
            $this->addFlash('info', 'edition erreur');

        $args = array('myform' => $form->createView());
        return $this->render('utilisateur/addU.html.twig', $args);
    }
    /**
     * @Route("/modifAllUser", name="_modif_all")
     */
    public function editAll(EntityManagerInterface $em,Request $request): Response
    {
        $utilisateursRepository = $em->getRepository('App:Utilisateur');
        $users = $utilisateursRepository->findAll();

        $user = $utilisateursRepository->find($this->getParameter('me'));
        if($user != null){
            if($user->getIsAdmin() == true) {
                return $this->render('utilisateur/editAll.html.twig', ['users' => $users, 'me' => $this->getParameter('me')]);
            } else {
                throw $this->createNotFoundException('Tu essaies d\'acceder à une page admin');
            }
        }else {
            throw $this->createNotFoundException("L utilisateur n'existe pas");
        }

    }
    /**
     * @Route("/supprimer/{idUser}", name="_supprimer")
     */
    public function supprimeUserAction(EntityManagerInterface $em, Request $request,$idUser): Response
    {
        $userRepository = $em->getRepository('App:Utilisateur');

        $me = $userRepository->find($this->getParameter('me'));
        if($me->getIsAdmin() == true){
            //Signifie qu'il est admin

            $panierRepository = $em->getRepository('App:Panier');
            $panierproduitRepository = $em->getRepository('App:PanierProduit');

            $user = $userRepository->find($idUser);
            $panier=$user->getPanier();
            if($user != null ){
                $panierproduits =$panierproduitRepository->findBy(['panier' => $panier]);
                foreach ($panierproduits as $panierproduit){
                    $produit = $panierproduit->getProduit();
                    $produit->setQuantite($produit->getQuantite() + $panierproduit->getQuantite());
                    $em->persist($produit);
                    $em->remove($panier);
                    $em->remove($panierproduit);
                }
            }
            else {
                throw $this->createNotFoundException("L'utilisateur n'existe pas");
            }
            $em->remove($user);
            $em->flush();
            return $this->redirectToRoute('utilisateur_modif_all');
        } else {
            throw $this->createNotFoundException('Tu essaies d\'acceder à une page admin');
        }
    }

}
