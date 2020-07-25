<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GlobalController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index()
    {
        return $this->render('global/accueil.html.twig', [
        ]);
    }

    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur->setRoles('ROLE_USER');
            $password = $utilisateur->getPassword();
            $encoded = $encoder->encodePassword($utilisateur, $password);
            $utilisateur->setPassword($encoded);
            $em->persist($utilisateur);
            $em->flush();
            $this->addFlash('success', "Vous êtes bien inscrit!");
            return $this->redirectToRoute('accueil');
        }

        return $this->render('global/inscription.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('global/login.html.twig', [
            'error' => $error,
            'lastUserName' => $lastUsername
        ]);
    }


    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }

}
