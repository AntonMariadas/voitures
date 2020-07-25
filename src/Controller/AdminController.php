<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Entity\RechercheVoiture;
use App\Form\RechercheVoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(VoitureRepository $repo, PaginatorInterface $paginator, Request $request)
    {
           // On lit le formulaire à l'entité même si il n'est pas en bdd !
        $recherche = new RechercheVoiture();
        $form = $this->createForm(RechercheVoitureType::class, $recherche);

        // On récupère la requête du formulaire et les informations sont présentes dans $recherche !
        $form->handleRequest($request);

        $voitures = $paginator->paginate(
            //La méthode devra prendre en compte l'entité RechercheVoiture pour réaliser la requête de selection
            //la méthode dépend de l'objet recherche
            $repo->findAllWithPagination($recherche),  
            $request->query->getInt('page', 1), /*page number, de base on accède à la première page(on ne modifie pas)*/
            6 /*limit per page*/
        );
        
        return $this->render('voiture/voitures.html.twig', [
              'voitures' => $voitures,
              'form' => $form->createView(),
              'admin' => true,
              'isModif' => false
        ]);
    }


    /**
     * @Route("/admin/modifier/{id}", name="admin_modif")
     * @Route("/admin/ajouter", name="admin_ajout")
     */
    public function ajoutEtModif(Voiture $voiture = null, Request $request, EntityManagerInterface $entityManager)
    {
      if (!$voiture) {
        $voiture = new Voiture();
      }
      
      $isModif = $voiture->getId() ? true: false; 
      

      $form = $this->createForm(VoitureType::class, $voiture);

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
          $entityManager->persist($voiture);
          $entityManager->flush();
          if ($isModif) {
            $this->addflash('success', 'Voiture modifiée avec succès!');
          } else {
            $this->addflash('success', 'Voiture ajoutée avec succès!');
          }

          return $this->redirectToRoute('admin');
      }


        return $this->render('admin/ajoutEtModif.html.twig', [
              'form' => $form->createView(),
              'voiture' => $voiture,
              'isModif' => $isModif
        ]);
    }


    /**
     * @Route("/admin/supprimer/{id}", name="admin_supp")
     */
    public function suppression(Voiture $voiture, EntityManagerInterface $em)
    {
      $em->remove($voiture);
      $em->flush();
      $this->addflash('success', 'Voiture supprimée avec succès!');

      return $this->redirectToRoute('admin');
    }

}
