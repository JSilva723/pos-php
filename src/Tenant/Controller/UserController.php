<?php

declare(strict_types=1);

namespace Tenant\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Tenant\Entity\User;
use Tenant\Form\ChangePasswordType;
use Tenant\Form\UserType;

class UserController extends AbstractController
{
    public function show(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        /** @var User */
        $user = $this->getUser();

        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('tenant_user_show', [], Response::HTTP_SEE_OTHER);
        }

        $passForm = $this->createForm(ChangePasswordType::class);
        $passForm->handleRequest($request);

        if ($passForm->isSubmitted() && $passForm->isValid()) {
            $newPassword = $passForm->get('plainPassword')->getData();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $newPassword,
            );
            $user->setPassword($hashedPassword);
            $entityManager->flush();

            return $this->redirectToRoute('tenant_logout', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/show.html.twig', [
            'name' => $user->getUsername(),
            'userForm' => $userForm,
            'passForm' => $passForm,
        ]);
    }
}
