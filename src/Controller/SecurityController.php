<?php
namespace App\Controller;

use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class SecurityController extends AbstractController
{
    #[Route('/api/login', name: 'app_login', methods: ['POST'])]
    public function login(#[CurrentUser] User $user = null) : Response {
        return $this->json([
            'user' => $user?->getId(),
        ]);
    }
    #[Route('/api/logout', name: 'app_logout', methods: ['POST'])]
    public function logout(Request $request) : Response {
        return $this->json([
            'message' => $request->getContent()
        ]);
    }
}
