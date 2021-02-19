<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Form\VilleType;
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
    public function add(EntityManagerInterface $em, Request $request)
    {
        //$user = $this->get('security.context')->getToken()->getUser();
        $user = $em->getRepository(User::class)->find($this->getUser());
        $etat = $this->getDoctrine()
            ->getRepository(Etat::class)
            ->find('1');

        $sortie = new Sortie();
        $sortie->setEtat($etat);
        $lieu = new Lieu();
        $ville = new Ville();



        $sortie->setCampus( $user->getCampus());


        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $villeForm = $this->createForm(VilleType::class, $ville);
        $lieuForm = $this->createForm(LieuType::class, $lieu);
        $sortieForm->handleRequest($request);
        $villeForm->handleRequest($request);
        $lieuForm->handleRequest($request);

        dump($sortieForm);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid() && $villeForm->isSubmitted() && $villeForm->isValid() && $lieuForm->isSubmitted() && $lieuForm->isValid())
        {
            $sortie->setOrganisateur($user);
            $em->persist($ville);
            $em->flush();
            $lieu->setVille($ville);
            $em->persist($lieu);
            $em->flush();
            $sortie->setLieu($lieu);
            $em->persist($sortie);
            $em->flush();

            $this->addFlash('success', 'Nouvelle sortie publiée');
            return $this->redirectToRoute("main_home");
        }

        return $this->render("sortie/add.html.twig", [
            'sortieForm'=>$sortieForm->createView(),
            'lieuForm'=>$lieuForm->createView(),
            'villeForm'=>$villeForm->createView(),
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

}
