<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Editeur;
use App\Entity\Livre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/admin/accueil", name="accueil")
     */
    public function index(): Response
    {
        $nbAuteurs=count($this->getDoctrine()->getRepository(Auteur::class)->findAll());
        $nbAEditeurs=count($this->getDoctrine()->getRepository(Editeur::class)->findAll());
        $nbLivres=count($this->getDoctrine()->getRepository(Livre::class)->findAll());
        $nbCategories=count($this->getDoctrine()->getRepository(Categorie::class)->findAll());

        return $this->render('accueil/index.html.twig', [
            'titre'=>'Accueil',
            'soustitre'=>'',
            'lien'=>$this->generateUrl('accueil'),
            'nbAuteurs' => $nbAuteurs,
            'nbEditeurs' => $nbAEditeurs,
            'nbLivres' => $nbLivres,
            'nbCategories' => $nbCategories,

        ]);
    }
}
