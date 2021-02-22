<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Service\FileUploader;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, FileUploader $fileUploader): Response
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
            /**@var UploadedFile $photo */
            $photo = $registerForm->get('photo')->getData();
            if ($photo)
            {
                $photoFileName = $fileUploader->upload($photo);
                $user->setphoto($photoFileName);

            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Inscription réalisée avec succés !');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/register.html.twig', [
            "registerForm" => $registerForm->createView(),

        ]);
    }

    /**
     * @Route("/profil", name="user_profil")
     */
    public function modifierProfil(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, FileUploader $fileUploader): Response
    {


        $user = $em->getRepository(User::class)->find($this->getUser());
        $registerForm = $this->createForm(RegisterType::class, $user);
        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid())
        {
            //hash password
            $hashed = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashed);

            /**@var UploadedFile $photo */
            $photo = $registerForm->get('photo')->getData();
            if ($photo)
            {
                $photoFileName = $fileUploader->upload($photo);
                $user->setphoto($photoFileName);

            }

            $em->flush();

            $this->addFlash('success', 'Votre profil a bien été mis à jour !');
            return $this->redirectToRoute('main_home');
        }

        return $this->render('user/register.html.twig', [
            'user' => $user,
            'registerForm_title' => "Mon profil",
            'registerForm' => $registerForm->createView(),
        ]);
    }


}
