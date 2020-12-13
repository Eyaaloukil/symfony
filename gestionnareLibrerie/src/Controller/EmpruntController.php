<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EmpruntRepository;
use App\Entity\Emprunt;

class EmpruntController extends AbstractController
{
    /**
     * @Route("/admin/emprunt", name="emprunt_index")
     */
    public function index(EmpruntRepository $empruntRepository): Response
    {
        return $this->render('emprunt/index.html.twig', [
            'titre'=>'Emprunt',
            'soustitre'=>'Index',
            'lien'=>$this->generateUrl('emprunt_index'),
            'emprunts' => $empruntRepository->findAll(),
        ]);
    }
}
