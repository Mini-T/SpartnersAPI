<?php
namespace App\Controller;

use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use \Symfony\Component\Routing\Annotation\Route;
use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\Security\Http\Attribute\CurrentUser;
use \App\Entity\User;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(#[CurrentUser] User $user = null) : Response {
        return $this->json([
            'user' => $user?->getId(),
        ]);
    }
}
