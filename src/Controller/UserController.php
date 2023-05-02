<?php

namespace App\Controller;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\DTO\UserDTO;
use App\Entity\User;
use App\State\UserProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[ApiResource(
    operations: [
        new Get()
    ],
)]
#[Get(security: "is_granted('ROLE_USER')")]
class UserController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    #[Route('/api/me', name: 'app_me', methods: ['GET'])]
    public function getCurrentUser(): JsonResponse {
        $userObj = $this->security->getUser();
        $userDto = new UserDTO($userObj->getFirstname(), $userObj->getLastname(), $userObj->getEmail(), $userObj->getSex(), $userObj->getCity(), $userObj->getLevel() ,$userObj->getObjective() ,$userObj->getDescription());
        return $this->json($userDto);
    }

    #[Route('/api/me/change')]
    public function patchCurrentUser(Request $request){

    }
}