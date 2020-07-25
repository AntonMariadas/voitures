<?php

namespace App\Controller;

use App\Entity\RechercheVoiture;
use App\Form\RechercheVoitureType;
use App\Repository\VoitureRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VoitureController extends AbstractController
{
    /**
     * @Route("/client/voitures", name="voitures")
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
            'admin' => false
        ]);
    }

}
