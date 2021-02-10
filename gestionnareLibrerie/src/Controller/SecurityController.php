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
use App\Repository\CategorieRepository;

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
            
            'livres' => $livreRepository->findBy(
                array(),
                array('id' => 'DESC'),
                3,
                0
            ),
        ]);
    }
    /**
     * @Route("app/emprunt/{id}", name="emprunt_show", methods={"GET"})
     */
    public function show(Livre $livre): Response
    {
        $startdate=strtotime(date("m/d/Y"));
        $em = $this->getDoctrine()->getManager();

        $enddate=strtotime("+6 days", $startdate);
        $nbLivres=count($this->getDoctrine()->getRepository(Emprunt::class)->findBy(['Livre'=>$livre]));

        return $this->render('app/show.html.twig', [

            'livre' => $livre,
            'startdate' =>$startdate,
            'enddate' =>$enddate,
            'count'=>$nbLivres

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
/**
     * @Route("app/user/{id}", name="emprunt_list", methods={"GET"})
     */
    public function list(Abonne $abonne): Response
    {

        $nbLivres=$this->getDoctrine()->getRepository(Emprunt::class)->findBy(['Abonne'=>$abonne]);

        return $this->render('app/list.html.twig', [

  
            'emprunts'=>$nbLivres

        ]);
    }
 /**
     * @Route("/delete/{id}", name="delete_emprunt")
     */
    public function delete(Request $request, Livre $livre): Response
    {
        $abonne = $this->get('security.token_storage')->getToken()->getUser();
        $emprunt=$this->getDoctrine()->getRepository(Emprunt::class)->findOneBy(['Abonne'=>$abonne,'Livre'=>$livre]);

        $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($emprunt);
            $entityManager->flush();
        

        return $this->redirectToRoute('app');
    }
     /**
     * @Route("/app/livres",name="app_livres", methods={"GET"})
     */
    public function tous(LivreRepository $livreRepository,Request $request,CategorieRepository $categorieRepository): Response{
        
        $categ_id=$request->query->get('category_id');
        
        if(isset($categ_id)){
            $livres = $livreRepository->findBy([
                'categorie' => $categ_id            ]);
        }
        else{
            $livres=$livreRepository->findAll();
        }

        return $this->render('app/listlivres.html.twig', [
            'livres'=>$livres,
            'categories'=>$categorieRepository->findAll()
           
        ]);
    }
}
