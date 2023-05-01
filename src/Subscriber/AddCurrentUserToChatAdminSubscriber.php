<?php

namespace App\Subscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Chat;
use App\Entity\Message;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AddCurrentUserToChatAdminSubscriber implements EventSubscriberInterface
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

        $chat = $event->getControllerResult();

        if (!$chat instanceof Chat || !$chat->getName() || !$event->isMainRequest()) {
            return;
        }
        $owner = $this->security->getUser();
        $chat->addUser($owner);
        $date = new \DateTime();

        if (!$owner instanceof User) {
            return;
        }

        $chat->setAdmin($owner);
        $chat->setDateCreated($date);
    }
}