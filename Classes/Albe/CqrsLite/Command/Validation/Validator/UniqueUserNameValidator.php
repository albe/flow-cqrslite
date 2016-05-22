<?php
namespace Albe\CqrsLite\Command\Validation\Validator;

/*
 * This file is part of the Albe.CqrsLite package.
 */

use Albe\CqrsLite\Query\Domain\Repository\UserRepository;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Validation\Validator\AbstractValidator;

/**
 * Validator for uniqueness of usernames. This is a business rule and not user input validation.
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
        $username = $value->getUsername();
        if ($this->users->countByUsername($username) > 0) {
            $this->addError('This username is already taken.', 1463534199);
        }
    }
}
