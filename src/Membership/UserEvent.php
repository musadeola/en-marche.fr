<?php

namespace AppBundle\Membership;

use AppBundle\Entity\Adherent;
use AppBundle\Entity\SubscriptionType;
use Symfony\Component\EventDispatcher\Event;

class UserEvent extends Event
{
    private $user;
    private $allowEmailNotifications;
    private $allowMobileNotifications;
    private $subscriptions;
    private $unsubscriptions;

    public function __construct(
        Adherent $adherent,
        bool $allowEmailNotifications = null,
        bool $allowMobileNotifications = null,
        array $oldEmailsSubscriptions = []
    ) {
        $this->user = $adherent;
        $this->allowEmailNotifications = $allowEmailNotifications;
        $this->allowMobileNotifications = $allowMobileNotifications;

        if ($oldEmailsSubscriptions) {
            $this->subscriptions = array_values(array_map(function (SubscriptionType $subscription) {
                return $subscription->getCode();
            }, array_diff($adherent->getSubscriptionTypes(), $oldEmailsSubscriptions)));
            $this->unsubscriptions = array_values(array_map(function (SubscriptionType $subscription) {
                return $subscription->getCode();
            }, array_diff($oldEmailsSubscriptions, $adherent->getSubscriptionTypes())));
        }
    }

    public function getUser(): Adherent
    {
        return $this->user;
    }

    public function allowEmailNotifications(): ?bool
    {
        return $this->allowEmailNotifications;
    }

    public function allowMobileNotifications(): ?bool
    {
        return $this->allowMobileNotifications;
    }

    public function getSubscriptions(): ?array
    {
        return $this->subscriptions;
    }

    public function getUnsubscriptions(): ?array
    {
        return $this->unsubscriptions;
    }
}
