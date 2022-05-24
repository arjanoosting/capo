<?php

namespace Gizmo\CapoBundle\Event;

use Gizmo\CapoBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use LdapTools\Bundle\LdapToolsBundle\Event\LoadUserEvent;

class LoadUserEventListener
{
    public function beforeLoadUser(LoadUserEvent $event)
    {
    }

    public function afterLoadUser(LoadUserEvent $event)
    {
        $user = $event->getUser();
        $ldapObject = $event->getLdapObject();

        $user->updateFromLdapObject($ldapObject);
    }
}
