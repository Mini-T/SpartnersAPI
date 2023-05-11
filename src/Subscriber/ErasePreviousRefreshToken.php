<?php

namespace App\Subscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\RefreshToken;
use App\Repository\RefreshTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Doctrine\RefreshTokenRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ErasePreviousRefreshToken implements EventSubscriberInterface
{

    private $entityManager;

    public function __construct(RefreshTokenRepository $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => ['erasePreviousRefreshToken', EventPriorities::PRE_WRITE]
        ];
    }

    public function erasePreviousRefreshToken(ResponseEvent $event)
    {
        if ($event->getRequest()->getRequestUri() === ('/api/login') && $event->getResponse()->isOk()) {
            $email = json_decode($event->getRequest()->getContent(), associative: true)["email"];
            $res = $this->entityManager->findByEmail($email);
            if(count($res) > 1){
            $this->entityManager->remove($res[0], flush: true);
            }
        }

    }
}