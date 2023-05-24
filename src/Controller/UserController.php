<?php

namespace App\Controller;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\DTO\UserDTO;
use App\Entity\User;
use App\State\UserProcessor;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[ApiResource(
    operations: [
        new Get(),
        new Patch()
    ],
)]
#[Get(security: "is_granted('ROLE_USER')")]
class UserController extends AbstractController
{
    private $entityManager;
    private $security;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }
    #[Route('/api/me', name: 'app_me', methods: ['GET'])]
    public function getCurrentUser(): JsonResponse {
        $userObj = $this->security->getUser();
        $userDto = new UserDTO($userObj->getFirstname(),$userObj->getEmail(), $userObj->getLastname(), $userObj->getSex(), $userObj->getCity(), $userObj->getDescription(), $userObj->getLevel() ,$userObj->getObjective());
        return $this->json($userDto);
    }

    #[Route('/api/changeUserInformation', name: 'app_changeme', methods: ['PATCH'])]
    public function patchCurrentUser(Request $request)
    {
        $user = $this->security->getUser();

        // Récupère les données du formulaire
        $data = json_decode($request->getContent(), true);
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'sex':
                    $user->setSex($value);
                    break;
                case 'city':
                    $user->setCity($value);
                    break;
                case 'firstname':
                    $user->setFirstname($value);
                    break;
                case 'lastname':
                    $user->setLastname($value);
                    break;
                case 'level':
                    $user->setLevel($value);
                    break;
                case 'objective':
                    $user->setObjective($value);
                    break;
                case 'description':
                    $user->setDescription($value);
                    break;
                case 'latitude':
                    $user->setLatitude($value);
                    break;
                case 'longitude':
                    $user->setLongitude($value);
                    break;
                default:
                    $responseData = array(
                        "message" => "Invalid Arguments"
                    );
                    return $this->json($responseData, 400);
                }
            }
        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            throw $exception;
        }

        return $this->json([
            'Success' => 'User was updated Successfully'
        ]);
        }
}