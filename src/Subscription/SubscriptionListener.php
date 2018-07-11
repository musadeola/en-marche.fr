<?php

namespace AppBundle\Subscription;

use AppBundle\Membership\UserEvent;
use AppBundle\Membership\UserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SubscriptionListener implements EventSubscriberInterface
{
    private $subscriptionHandler;

    public function __construct(SubscriptionHandler $subscriptionHandler)
    {
        $this->subscriptionHandler = $subscriptionHandler;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserEvents::USER_CREATED => 'addSubscriptionTypeToAdherent',
        ];
    }

    public function addSubscriptionTypeToAdherent(UserEvent $event): void
    {
        if (false === $event->allowEmailNotifications() && false === $event->allowMobileNotifications()) {
            return;
        }

        $this->subscriptionHandler->addDefaultTypesToAdherent(
            $event->getUser(),
            $event->allowEmailNotifications() ?? false,
            $event->allowMobileNotifications() ?? false
        );
    }
}
