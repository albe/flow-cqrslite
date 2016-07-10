<?php
namespace Albe\CqrsLite\Query\Domain\Model;

/*
 * This file is part of the Albe.CqrsLite package.
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * This is the read-side equivalent of the write-side User model and captures all information that is necessary for
 * users to be able to work with the system in the expected way.
 *
 * Notice that this is only slightly different from the write-side model in this case, but could in fact look completely
 * different depending on the use case.
 *
 * @Flow\Entity
 */
class User
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $username;

    /**
     * User constructor.
     * @param string $username
     * @param string $type
     */
    public function __construct($username, $type)
    {
        $this->username = $username;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
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
