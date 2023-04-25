<?php

namespace App\Subscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Message;
use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class AddCurrentUserSubscriber implements EventSubscriberInterface
{

    private $tokenStorage;
    private $requestStack;
    private $security;
    private $logger;

    public function __construct(TokenStorageInterface $tokenStorage, RequestStack $requestStack, Security $security, LoggerInterface $logger){
        $this->tokenStorage=$tokenStorage;
        $this->requestStack = $requestStack;
        $this->security = $security;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['attachUser', EventPriorities::PRE_WRITE]
        ];
    }

    public function attachUser(ViewEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request->isMethod('POST')) {
            return;
        }

        $message = $event->getControllerResult();
        if (!$message instanceof Message || !$message ->getContent() || !$event->isMainRequest()) {
            return;
        }

        $owner = $this->security->getUser();

        if (!$owner instanceof User) {
            return;
        }

        $message->setSender($owner);
        $this->logger->info(sprintf('Owner attached to article #%d.', $message->getId()));
    }
}