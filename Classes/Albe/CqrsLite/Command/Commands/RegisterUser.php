<?php
namespace Albe\CqrsLite\Command\Commands;

/*
 * This file is part of the Albe.CqrsLite package.
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * A command for registering a new user. It is a simple DTO without any special requirements of implementation.
 * You can freely annotate fields here for user input validation.
 *
 * You could also add validators that do business rules, but it is best to keep those validations separated.
 * You can use custom validators that you annotate in your actions instead.
 *
 * @Flow\Proxy(false)
 */
class RegisterUser
{
    /**
     * @var string
     * @Flow\Validate(type="NotEmpty")
     * @Flow\Validate(type="Alphanumeric")
     */
    protected $username;

    /**
     * @var string
     * @Flow\Validate(type="NotEmpty")
     * @Flow\Validate(type="RegularExpression",options={"regularExpression"="/^(User|Moderator|Admin)$/"})
     */
    protected $type;

    /**
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
