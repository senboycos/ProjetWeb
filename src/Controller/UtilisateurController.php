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
            $user->setIsadmin(false);
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

}
