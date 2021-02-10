<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AbonneRepository;
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
    public function index(AbonneRepository $abonneRepository,EmpruntRepository $empruntRepository,Request $request): Response
    {   
        $user_id=$request->query->get('user_id');
        if($user_id=='all' || !isset($user_id)){
            $emprunts=$empruntRepository->findAll();
            $abonnee=null;

        }
        else{
            $emprunts = $empruntRepository->findBy([
                'Abonne' => $user_id            ]);
                $abonnee=$abonneRepository->findOneBy(['id'=>$user_id]);

        }
        $abonnes=$abonneRepository->findAll();
        return $this->render('emprunt/index.html.twig', [
            'titre'=>'Emprunt',
            'soustitre'=>'Index',
            'lien'=>$this->generateUrl('emprunt_index'),
            'emprunts' => $emprunts,
            'abonnes'=>$abonnes,
            'abonnee'=>$abonnee
        ]);
    }
    
}
