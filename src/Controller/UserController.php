<?php

namespace App\Controller;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\DTO\UserDTO;
use App\Entity\User;
use App\Repository\SportsHallRepository;
use App\State\UserProcessor;
use App\Validators\ObjectiveValidator;
use DateTime;
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
    private SportsHallRepository $sportsHallRepository;
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(Security $security, EntityManagerInterface $entityManager, SportsHallRepository $sportsHallRepository)
    {
        $this->sportsHallRepository = $sportsHallRepository;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }
    #[Route('/api/me', name: 'app_me', methods: ['GET'])]
    public function getCurrentUser(): JsonResponse {
        $userObj = $this->security->getUser();
        if(!$userObj) {
            return new JsonResponse('Authenticate first', 401);
        }
        $sportsHall = $userObj->getSportsHall()->getId();
        $userDto = new UserDTO($userObj->getFirstname(), $userObj->getLastname(),$userObj->getEmail(), $userObj->getSex(), $userObj->getCity(), $userObj->getDescription(), $userObj->getLevel() ,$userObj->getObjective(), $userObj->getAge(), $userObj->getJoinDate(), $userObj->getLatitude(), $userObj->getLongitude(), $sportsHall, $userObj->isVisible());
        return $this->json($userDto);
    }

    #[Route('/api/changeUserInformation', name: 'app_changeme', methods: ['PATCH'])]
    public function patchCurrentUser(Request $request)
    {
        $user = $this->security->getUser();
        if(!$user) {
            return new JsonResponse('Authenticate first', 401);
        }
        // Récupère les données du formulaire
        $data = json_decode($request->getContent(), true);
        if (!$data){
            return $this->json((
                'No arguments given'
            ));
        }
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'sex':
                    $user->setSex($value);
                    break;
                case 'city':
                    $user->setCity($value);
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
                case 'sportsHall':

                    $sportsHall = $this->sportsHallRepository->find($value);
                    if ($sportsHall == null) {
                        return $this->json('Invalid Arguments', 400);
                    }
                    $user->setSportsHall($sportsHall);
                    break;
                case 'visible':
                    $user->setVisible($value);
                    break;
                default:
                    $responseData = array(
                        "message" => "Invalid Arguments"
                    );
                    return $this->json($responseData, 400);
                }
                $sportsHallId = $user->getSportsHall()->getId();
            $userDTO = new UserDTO($user->getFirstname(), $user->getLastname(), $user->getEmail(), $user->getSex(), $user->getCity(), $user->getDescription(), $user->getLevel(), $user->getObjective(), $user->getAge(), $user->getJoinDate(), $user->getLatitude(), $user->getLongitude(), $sportsHallId, $user->isVisible());
        }
        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            throw $exception;
        }

        return $this->json([
            $userDTO
        ]);
        }
}