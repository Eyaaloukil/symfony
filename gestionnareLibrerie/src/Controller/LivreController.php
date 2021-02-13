<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/livre")
 */
class LivreController extends AbstractController
{
    /**
     * @Route("/", name="livre_index", methods={"GET"})
     */
    public function index(LivreRepository $livreRepository): Response
    {
        return $this->render('livre/index.html.twig', [
            'titre'=>'Livre',
            'soustitre'=>'Index',
            'lien'=>$this->generateUrl('livre_index'),
            'livres' => $livreRepository->findAll(),
        ]);
    }
    

    /**
     * @Route("/new", name="livre_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile=$form->get('image')->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';


            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = "test1".'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $livre->setImage($newFilename);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($livre);
            $entityManager->flush();

            return $this->redirectToRoute('livre_index');
        }

        return $this->render('livre/new.html.twig', [
            'livre' => $livre,
            'titre'=>'Livre',
            'soustitre'=>'Nouveau',
            'lien'=>$this->generateUrl('livre_index'),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="livre_show", methods={"GET"})
     */
    public function show(Livre $livre): Response
    {
        return $this->render('livre/show.html.twig', [

            'titre'=>'Livre',
            'soustitre'=>'Afficher',
            'livre' => $livre,
            'lien'=>$this->generateUrl('livre_index'),

        ]);
    }

    /**
     * @Route("/{id}/edit", name="livre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Livre $livre): Response
    {

        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('livre_index');
        }

        return $this->render('livre/edit.html.twig', [

            'titre'=>'Livre',
            'soustitre'=>'Editer',
            'livre' => $livre,
            'lien'=>$this->generateUrl('livre_index'),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="livre_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Livre $livre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($livre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('livre_index');
    }

    /**
     * @Route("/supprimer/{id}", name="livre_delete_2")
     */
    public function supprimer(Request $request, int $id = -1): Response
    {
        if ($id <= 0)
            return $this->redirectToRoute('livre_index');
        else {
            $livre = $this->getDoctrine()->getRepository(Livre::class)->findOneBy(['id' => $id]);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($livre);
            $entityManager->flush();

            return $this->redirectToRoute('livre_index');

        }

    }
}
