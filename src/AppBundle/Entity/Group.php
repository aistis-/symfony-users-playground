<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\Group as BaseGroup;

/**
 * @ORM\Entity
 * @ORM\Table(name="groups")
 */
class Group extends BaseGroup
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
     * Users who belong to the group.
     *
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="groups")
     */
    protected $users;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * Convert object to a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
