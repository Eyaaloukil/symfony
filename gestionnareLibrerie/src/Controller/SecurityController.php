<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Abonne;
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
}
