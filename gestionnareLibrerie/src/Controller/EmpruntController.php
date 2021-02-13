<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AbonneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EmpruntRepository;
use App\Entity\Emprunt;
use Symfony\Component\Mime\Email;

use Symfony\Component\Mailer\MailerInterface;

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
    /**
     * @Route("/sendmail/{id}", name="send_mail", methods={"GET","POST"})
     */
    public function sendEmail(Emprunt $emprunt,MailerInterface $mailer,Request $request)
    {
      
        $email = (new Email())
            ->from('belghuithkadhem@gmail.com')
            ->to($emprunt->getAbonne()->getEmail())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('[Rappel] Retourner le livre')
            ->html('<p>Bonjour</p><p>Priére de retourner le livre '.$emprunt->getLivre()->getTitre().' emrpunté le '.$emprunt->getDateEmprunt()->format('Y-m-d'). ' le plus tot possible</p>
            <p>Bien Cordialement</p>');

        $mailer->send($email);
        return $this->redirectToRoute("emprunt_index");

    }
    
}
