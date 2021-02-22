<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\User;
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
        return $this->render('sortie/afficherSortie.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }

    /**
     * @Route ("", name="sortie_add")
     */
    public function add(EntityManagerInterface $em, Request $request) : Response
    {
        //$user = $this->get('security.context')->getToken()->getUser();
        $user = $em->getRepository(User::class)->find($this->getUser());
        $sortie = new Sortie();

        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid())
        {
            if ($sortieForm->get('saveAndAdd')->isClicked()) {
                $etat = $this->getDoctrine()
                    ->getRepository(Etat::class)
                    ->find('2');
            } else {
                $etat = $this->getDoctrine()
                    ->getRepository(Etat::class)
                    ->find('1');
            }
            $sortie->setEtat($etat);
            $sortie->setOrganisateur($user);
            $sortie->setCampus($user->getCampus());

            $em->persist($sortie);
            $em->flush();

            $this->addFlash('success', 'Nouvelle sortie publiée');
            return $this->redirectToRoute("main_home");
        }

        return $this->render("sortie/add.html.twig", [
            'sortieForm'=>$sortieForm->createView(),
        ]);
    }
    /**
     * @Route ("/Sortie/AfficherSortie/{id}", name="afficherSortie", requirements={"id": "\d+"})
     */
    public function AfficherSortie(): Response
    {
        $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
        $sorties = $sortieRepository->{1};
        return $this->render('sortie/afficherSortie.html.twig',["sorties"=>$sorties]);
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

    /**
     * @Route ("/modifier/{id}", name="sortie_update", requirements={"id": "\d+"})
     */
     public function update (EntityManagerInterface $em, $id, Request $request) : Response
    {
    $sortie = $em->getRepository(Sortie::class)->find($id);
    $sortieForm = $this->createForm(SortieType::class, $sortie);
    $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid())
        {
            if ($sortieForm->get('saveAndAdd')->isClicked()) {
                $etat = $this->getDoctrine()
                    ->getRepository(Etat::class)
                    ->find('2');
            } else {
                $etat = $this->getDoctrine()
                    ->getRepository(Etat::class)
                    ->find('1');
            }
            $sortie->setEtat($etat);

            $em->persist($sortie);
            $em->flush();

            $this->addFlash('success', 'Sortie modifiée');
            return $this->redirectToRoute("main_home");
        }
        return $this->render('sortie/add.html.twig', [
            'sortie' => $sortie,
            'sortieForm_title' => "Modifer une sortie",
            'sortieForm' => $sortieForm->createView(),
            ]);
    }


}
