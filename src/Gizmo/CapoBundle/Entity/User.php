<?php
/*
    Capo, a web interface for querying multiple Cacti instances
    Copyright (C) 2013  Jochem Kossen <jochem@jkossen.nl>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace Gizmo\CapoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use LdapTools\Bundle\LdapToolsBundle\Security\User\LdapUserInterface;
use LdapTools\Object\LdapObject;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="Gizmo\CapoBundle\Entity\UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User implements UserInterface, LdapUserInterface
{
    /**
     * @var ArrayCollection $graph_selections
     *
     * @ORM\OneToMany(targetEntity="GraphSelection", mappedBy="user")
     */
    protected $graph_selections;

    /**
     * @var Group $group
     *
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="users")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=true)
     */
    protected $group;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=180)
     */
    protected $username;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;

    /**
     * Ldap Object Distinguished Name
     * @var string $dn
     */
    protected $dn;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $ldapGuid;

    /**
     * @ORM\Column(type="array")
     *
     * @var array
     */
    protected $roles;

    /**
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     * @var \DateTime|null
     */
    protected $lastLogin;

    /**
     * @var string $ldap_group
     *
     * @ORM\Column(name="ldap_group", type="string", length=255, unique=False, nullable=True)
     */
    protected $ldap_group;

    public function __construct()
    {
        $this->enabled = false;
        $this->roles = array();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getUsername();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ldapGuid
     *
     * @param string $ldapGuid
     *
     * @return AppUser
     */
    public function setLdapGuid($ldapGuid)
    {
        $this->ldapGuid = $ldapGuid;

        return $this;
    }

    /**
     * Get ldapGuid
     *
     * @return string
     */
    public function getLdapGuid()
    {
        return $this->ldapGuid;
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        $roles = $this->roles;

        //$roles[] = static::ROLE_DEFAULT;

        return array_unique($roles);
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     *
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    /**
     * Get group
     *
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * {@inheritdoc}
     */
    public function setEnabled($boolean)
    {
        $this->enabled = (bool) $boolean;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->enabled;
    }


    /**
     * Set group
     *
     * @param Group $group
     *
     * @return User
     */
    public function setGroup($group) {
        $this->group = $group;

        return $this;
    }
    /**
     * {@inheritDoc}
     */
    public function getDn() {
        return $this->dn;
    }

    /**
     * {@inheritDoc}
     */
    public function setDn($dn) {
        $this->dn = $dn;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set Group from DN
     *
     * @param String $dn
     * @return User
     */
    public function setLdapGroup($dn) {
        $dn_arr = preg_split('/,OU=/', stripslashes($dn));
        if (count($dn_arr) == 1) {
            $grpname = str_replace('OU=', '', $dn_arr[0]);
        } else {
            $grpname = str_replace('OU=', '', $dn_arr[1]);
        }

        $this->ldap_group = $grpname;

        return $this;
    }

    /**
     * Get LDAP group
     *
     * @return String Ldap group
     */
    public function getLdapGroup()
    {
        return $this->ldap_group;
    }

    /**
     * Gets the last login time.
     *
     * @return \DateTime|null
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * {@inheritdoc}
     */
    public function setLastLogin(\DateTime $time = null)
    {
        $this->lastLogin = $time;

        return $this;
    }

    public function updateFromLdapObject(LdapObject $ldapObject)
    {
        $this->setEnabled($ldapObject->get('enabled', true));
    }
}
