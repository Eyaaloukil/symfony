<?php

namespace App\Controller;

use App\Entity\Editeur;
use App\Form\EditeurType;
use App\Repository\EditeurRepository;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/editeur")
 */
class EditeurController extends AbstractController
{
    /**
     * @Route("/", name="editeur_index", methods={"GET"})
     */
    public function index(EditeurRepository $editeurRepository): Response
    {
        return $this->render('editeur/index.html.twig', [
            'titre'=>'Editeur',
            'soustitre'=>'Index',
            'lien'=>$this->generateUrl('editeur_index'),
            'editeurs' => $editeurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="editeur_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $editeur = new Editeur();
        $form = $this->createForm(EditeurType::class, $editeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($editeur);
            $entityManager->flush();

            return $this->redirectToRoute('editeur_index');
        }

        return $this->render('editeur/new.html.twig', [
            'titre'=>'Editeur',
            'soustitre'=>'Nouveau',
            'lien'=>$this->generateUrl('editeur_index'),
            'editeur' => $editeur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="editeur_show", methods={"GET"})
     */
    public function show(Editeur $editeur): Response
    {
        return $this->render('editeur/show.html.twig', [
            'titre'=>'Editeur',
            'soustitre'=>'Afficher',
            'editeur' => $editeur,
            'lien'=>$this->generateUrl('editeur_index'),

        ]);
    }

    /**
     * @Route("/{id}/edit", name="editeur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Editeur $editeur): Response
    {
        $form = $this->createForm(EditeurType::class, $editeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('editeur_index');
        }

        return $this->render('editeur/edit.html.twig', [
            'editeur' => $editeur,
            'lien'=>$this->generateUrl('editeur_index'),
            'titre'=>'Editeur',
            'soustitre'=>'Editer',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="editeur_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Editeur $editeur): Response
    {
        if ($this->isCsrfTokenValid('delete' . $editeur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($editeur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('editeur_index');
    }

    /**
     * @Route("/supprimer/{id}", name="editeur_delete_2")
     */
    public function supprimer(Request $request, int $id = -1): Response
    {
        if ($id <= 0)
            return $this->redirectToRoute('editeur_index');
        else {
            $editeur = $this->getDoctrine()->getRepository(Editeur::class)->findOneBy(['id' => $id]);
            $entityManager = $this->getDoctrine()->getManager();
            $livres=$editeur->getLivres();
                if(count($livres)==0){
                    $entityManager->remove($editeur);
                    $entityManager->flush();
                }
                else {
                    $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Vous ne pouvez pas supprimer cet editeur car il posséde un ou plusieurs livres !');
                }
            return $this->redirectToRoute('editeur_index');

        }

    }
}
