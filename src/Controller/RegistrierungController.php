<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class RegistrierungController extends AbstractController
{
    #[Route('/reg', name: 'reg')]
    public function reg(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {

        $regform = $this->createFormBuilder()
        ->add('username', TextType::class,[
            'label' => 'Mitarbeiter'])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'required' => true,
            'first_options' => ['label' => 'Password'],
            'second_options' => ['label' => 'Password Wiederholen']
        ])

        ->add('registrieren', SubmitType::class)
        ->getForm()
        ;

        $regform->handleRequest($request);

        if ($regform->isSubmitted()){
            $eingabe = $regform->getData();
            
            $user = new User();
            $user->setUsername($eingabe['username']);

            $user->setPassword(
                $passwordHasher->hashPassword($user, $eingabe['password'])
            );

            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('home'));

        }

        return $this->render('registrierung/index.html.twig', [
            'regform' => $regform->createView()
        ]);
    }
}
