<?php
namespace Albe\CqrsLite\Command\Commands;

/*
 * This file is part of the Albe.CqrsLite package.
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
class CreateUser
{
    /**
     * @var string
     * @Flow\Validate(type="NotEmpty")
     */
    protected $username;

    /**
     * @var string
     * @Flow\Validate(type="NotEmpty")
     */
    protected $type;

    /**
     * CreateUser constructor.
     * @param string $userName
     * @param string $type
     */
    public function __construct($userName, $type)
    {
        $this->username = $userName;
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
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}