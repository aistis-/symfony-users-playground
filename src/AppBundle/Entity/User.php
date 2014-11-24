<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
     * Identifier.
     *
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Groups user belongs to.
     *
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="users")
     * @ORM\JoinTable(name="users_groups")
     */
    protected $groups;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->groups = new ArrayCollection();
    }

    /**
     * Convert object to a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->username;
    }
}
