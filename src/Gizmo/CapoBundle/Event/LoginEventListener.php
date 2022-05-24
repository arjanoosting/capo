<?php

namespace Gizmo\CapoBundle\Event;

use Gizmo\CapoBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use LdapTools\Bundle\LdapToolsBundle\Event\LdapLoginEvent;
use LdapTools\Bundle\LdapToolsBundle\Security\User\LdapUserProvider;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class LoginEventListener
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var UserProviderInterface
     */
    protected $userProvider;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, LdapUserProvider $userProvider)
    {
        $this->em = $em;
        $this->userProvider = $userProvider;
    }

    /**
     * @param LdapLoginEvent $event
     */
    public function onLoginSuccess(LdapLoginEvent $event)
    {
        /** @var AppUser $user */
        $user = $event->getUser();

        try {
            $guid = $user->getLdapGuid();
            if (null === $guid || '' === $guid) {
                $ldapObject = $this->userProvider->getLdapUser('username', $user->getUsername());
                $user->setLdapGuid($ldapObject->get('guid'));
            } else {
                $ldapObject = $this->userProvider->getLdapUser('guid', $guid);
            }
            $user->updateFromLdapObject($ldapObject);
        } catch (UsernameNotFoundException $e) {
        }

        $this->userProvider->refreshUser($user);
        $user->setLastLogin(new \DateTime());

        $this->em->persist($user);
        $this->em->flush();
    }
}
