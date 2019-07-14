<?php
namespace Albe\CqrsLite\Query\Service;

/*
 * This file is part of the Albe.CqrsLite package.
 */

use Neos\Flow\Annotations as Flow;
use Albe\CqrsLite\Query\Domain\Model\User;
use Albe\CqrsLite\Query\Domain\Repository\UserRepository;
use Neos\Flow\Log\SystemLoggerInterface;

/**
 * An event handler implemented as Slot, that takes care of building the read-side of a User model, used for
 * displaying profile information or querying user lists.
 *
 * @Flow\Scope("singleton")
 */
class UserHandler
{
    /**
     * @var UserRepository
     * @Flow\Inject
     */
    protected $users;

    /**
     * @var SystemLoggerInterface
     * @Flow\Inject
     */
    protected $systemLogger;

    /**
     * @param array $payload
     * @return void
     */
    public function onUserRegistered(array $payload)
    {
        $this->systemLogger->log('new user registered.');

        $user = new User($payload['username'], $payload['type']);
        $this->users->add($user);
    }
}
