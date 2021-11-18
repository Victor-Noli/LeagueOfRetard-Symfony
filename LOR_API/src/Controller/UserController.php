<?php

namespace App\Controller;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\UserType;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    // public function infosUser(): Response
    // {

    //     $form = $this->createForm(DataUserType::class, new User());

    //     return $this->render('Account/register.html.twig', [
    //         'form' => $form->createView()
    //     ]);
    // }

public function new(DocumentManager $dm, Request $request)
{
    $form = $this->createForm(UserType::class, new User());

    $form->handleRequest($request);

    // if ($form->isSubmitted() && $form->isValid()) {
        $user = $form->getData();
        var_dump(($user));
        $dm->persist($user->getUser());
        $dm->flush();

        // return $this->redirect(...);
    // }

    return $this->render('Account/register.html.twig', [
        'form' => $form->createView()
    ]);
}
}
