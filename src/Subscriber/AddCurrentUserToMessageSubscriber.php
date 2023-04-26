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

class AddCurrentUserToMessageSubscriber implements EventSubscriberInterface
{

    private $requestStack;
    private $security;

    public function __construct(RequestStack $requestStack, Security $security){
        $this->requestStack = $requestStack;
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['autoConfig', EventPriorities::PRE_WRITE]
        ];
    }

    public function autoConfig(ViewEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request->isMethod('POST')) {
            return;
        }

        $date = new \DateTime();
        $message = $event->getControllerResult();
        if (!$message instanceof Message || !$message ->getContent() || !$event->isMainRequest()) {
            return;
        }

        $owner = $this->security->getUser();

        if (!$owner instanceof User) {
            return;
        }
        $message->setDatetime($date);
        $message->setSender($owner);
    }
}