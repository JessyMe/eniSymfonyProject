<?php

namespace App\Controller;

use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\User;

class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $registerForm = $this->createForm(RegisterType::class, $user);
            $user->setDateCreated(new \DateTime());

        $registerForm->handleRequest($request);
        if ($registerForm->isSubmitted() && $registerForm->isValid())
        {
            //hash password
            $hashed = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashed);

            $em->persist($user);
            $em->flush();
        }

        return $this->render('user/register.html.twig', [
            "registerForm" => $registerForm->createView()
        ]);
    }
}
