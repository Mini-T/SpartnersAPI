<?php

namespace App\Subscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Repository\RefreshTokenRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;


class ResponseSubscriber implements EventSubscriberInterface
{

    private $entityManager;

    public function __construct(RefreshTokenRepository $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => ['handleResponse', EventPriorities::PRE_WRITE]
        ];
    }

    public function handleResponse(ResponseEvent $event)
    {
        $req = $event->getRequest();
        $responseIsOk = $event->getResponse()->isOk();
        if ($req->getRequestUri() === ('/api/login') && $responseIsOk) {
            $email = json_decode($event->getRequest()->getContent(), associative: true)["email"];
            $res = $this->entityManager->findByEmail($email);
            if(count($res) > 1){
            $this->entityManager->remove($res[0], flush: true);
            }
        }
        if ($req->getRequestUri() === '/api/users' && $req->isMethod('POST') && $event->getResponse()->getStatusCode() === 201) {
            $event->setResponse(new JsonResponse(['message' => 'Successfully created user']));
        }
    }
}