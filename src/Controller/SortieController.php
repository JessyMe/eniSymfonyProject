<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortie")
 */
class SortieController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('sortie/index.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }

    /**
     * @Route ("", name="sortie_add")
     */
    public function add(EntityManagerInterface $em, Request $request)
    {
        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);
        if ($sortieForm->isSubmitted() && $sortieForm->isValid())
        {
            $em->persist($sortie);
            $em->flush();

            $this->addFlash('success', 'Nouvelle sortie publiée');
            return $this->redirectToRoute("main_home");
        }

        return $this->render("sortie/add.html.twig", [
            'sortieForm'=>$sortieForm->createView()
        ]);
    }

    /**
     * @Route ("/delete/{id}", name="sortie_delete", requirements={"id": "\d+"})
     */
    public function delete (EntityManagerInterface $em, $id)
    {
        $sortieRepo =$this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);
        $em->remove($sortie);
        $em->flush();
        $this->addFlash('success', "Sortie annulée");
        return $this->redirectToRoute("main_home");
    }

}
