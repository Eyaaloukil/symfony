<?php

namespace App\Controller;
use App\Entity\Livre;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Abonne;
use App\Entity\Emprunt;

use App\Repository\LivreRepository;
use App\Repository\AbonneRepository;

use App\Form\RegistrationType;
class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request,UserPasswordEncoderInterface $encoder){
        $abonne=new Abonne();
        $form=$this->createForm(RegistrationType::class,$abonne);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $hash=$encoder->encodePassword($abonne,$abonne->getPassword());
            $abonne->setPassword($hash);
            $entityManager = $this->getDoctrine()->getManager();    
                    $entityManager ->persist($abonne);
            $entityManager->flush();
            return $this->redirectToRoute('security_login');
        }
        return $this->render('security/registration.html.twig',
        ['form'=>$form->createView()
        ,

            'titre'=>'Connexion',
            'soustitre'=>'']);
    }
    /**
     * @Route("/connexion",name="security_login")
     */
    public function login(){
        return $this->render('security/login.html.twig');
    }
     /**
     * @Route("/deconnexion",name="security_logout")
     */
    public function logout(){
       
    }
    /**
     * @Route("/app",name="app")
     */
    public function index(LivreRepository $livreRepository): Response{
        return $this->render('app/index.html.twig', [
            
            'livres' => $livreRepository->findAll(),
        ]);
    }
    /**
     * @Route("app/{id}", name="emprunt_show", methods={"GET"})
     */
    public function show(Livre $livre): Response
    {
        $startdate=strtotime(date("m/d/Y"));
        $enddate=strtotime("+6 days", $startdate);

        return $this->render('app/show.html.twig', [

            'livre' => $livre,
            'startdate' =>$startdate,
            'enddate' =>$enddate

        ]);
    } /**
    * @Route("/{id}", name="confirm_emprunt", methods={"GET","POST"})
    */
   public function confirm(Request $request, Livre $livre): Response
   {

$emprunt=new Emprunt();
$emprunt->setLivre($livre);
$abonne = $this->get('security.token_storage')->getToken()->getUser();
$emprunt->setAbonne($abonne);


$emprunt->setDateEmprunt(new \DateTime());

$emprunt->setDateRetour(new \DateTime("+7 days"));

       $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($emprunt);
            $entityManager->flush();

            return $this->redirectToRoute('app');
   }


}
