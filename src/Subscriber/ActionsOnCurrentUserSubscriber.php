<?php

use ApiPlatform\Symfony\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ActionsOnCurrentUserSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['', EventPriorities::PRE_WRITE]
        ];
    }
}