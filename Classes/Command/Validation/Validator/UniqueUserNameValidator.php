<?php
namespace Albe\CqrsLite\Command\Validation\Validator;

/*
 * This file is part of the Albe.CqrsLite package.
 */

use Albe\CqrsLite\Command\Commands\RegisterUser;
use Albe\CqrsLite\Query\Domain\Repository\UserRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Exception;
use Neos\Flow\Validation\Validator\AbstractValidator;

/**
 * Validator for uniqueness of usernames.
 * Notice that this is a Command validator. This is a business rule and not user input validation.
 */
class UniqueUserNameValidator extends AbstractValidator
{
    /**
     * @var UserRepository
     * @Flow\Inject
     */
    protected $users;

    /**
     * @param mixed $value The value that should be validated
     * @return void
     */
    protected function isValid($value)
    {
        if (!$value instanceof RegisterUser) {
            throw new Exception('Can only validate RegisterUser commands. Got "' . gettype($value) . '".', 1463539549);
        }
        $username = $value->getUsername();
        if ($this->users->countByUsername($username) > 0) {
            $this->addError('This username is already taken.', 1463534199);
        }
    }
}
