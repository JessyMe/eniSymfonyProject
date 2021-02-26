<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, SluggerInterface $slugger): Response
    {
        $user = new User();
        $registerForm = $this->createForm(RegisterType::class, $user);
        $user->setDateCreated(new DateTime());
        $user->setRoles(['ROLE_USER']);


        $registerForm->handleRequest($request);
        if ($registerForm->isSubmitted() && $registerForm->isValid())
        {
            //hash password
            $hashed = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashed);

            $brochureFile = $registerForm->get('photo')->getData();

            if ($brochureFile)
            {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                try {
                    $brochureFile->move($this->getParameter('photoProfil_directory'), $newFilename);
                } catch (FileException $e){

                }
                $user->setPhoto($newFilename);
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Inscription réalisée avec succés !');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/register.html.twig', [
            'user' => $user,
            "registerForm" => $registerForm->createView(),

        ]);
    }

    /**
     * @Route("/profil", name="user_profil")
     */
    public function modifierProfil(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, SluggerInterface $slugger): Response
    {


        $user = $em->getRepository(User::class)->find($this->getUser());
        $registerForm = $this->createForm(RegisterType::class, $user);
        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid())
        {
            //hash password
            $hashed = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashed);

           $brochureFile = $registerForm->get('photo')->getData();

            if ($brochureFile)
            {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                try {
                    $brochureFile->move($this->getParameter('photoProfil_directory'), $newFilename);
                } catch (FileException $e){}
                $user->setPhoto($newFilename);
            }

            $em->flush();

            $this->addFlash('success', 'Votre profil a bien été mis à jour !');
            return $this->redirectToRoute('main_home');
        }
        $photo = $user->getPhoto();

        return $this->render('user/register.html.twig', [
            'photo' => $photo,
            'user' => $user,
            'registerForm_title' => "Mon profil",
            'registerForm' => $registerForm->createView(),
        ]);
    }


}
