<?php
namespace Albe\CqrsLite\Command\Domain\Model;

/*
 * This file is part of the Albe.CqrsLite package.
 */

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * This is a write-side model to capture information for keeping business rules intact.
 *
 * We specially annotate the "unique username" business constraint to the database here, but this is only for
 * double-safety. Most other business rules can probably not be captured by database constraints anyway.
 *
 * @Flow\Entity
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="unique_name", columns={"username"})})
 */
class User
{
    /**
     * @var string
     */
    protected $username;

    /**
     * User constructor.
     * @param string $username
     */
    public function __construct($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
}
