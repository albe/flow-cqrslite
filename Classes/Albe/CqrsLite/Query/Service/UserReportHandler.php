<?php
namespace Albe\CqrsLite\Query\Service;

/*
 * This file is part of the Albe.CqrsLite package.
 */

use TYPO3\Flow\Annotations as Flow;
use Albe\CqrsLite\Query\Domain\Model\User;
use Albe\CqrsLite\Query\Domain\Model\UserReport;
use Albe\CqrsLite\Query\Domain\Repository\UserReportRepository;
use TYPO3\Flow\Log\SystemLoggerInterface;

/**
 * @Flow\Scope("singleton")
 */
class UserReportHandler
{
    /**
     * @var UserReportRepository
     * @Flow\Inject
     */
    protected $userReports;

    /**
     * @var SystemLoggerInterface
     * @Flow\Inject
     */
    protected $systemLogger;

    /**
     * @param User $user
     * @return void
     */
    public function onUserCreated(User $user)
    {
        $this->systemLogger->log('onUserCreated');
        /* @var $userReport UserReport */
        $userReport = $this->userReports->findOneByType($user->getType());
        if (!$userReport) {
            $userReport = new UserReport($user->getType());
            $this->userReports->add($userReport);
        } else {
            $userReport->increaseCount();
            $this->userReports->update($userReport);
        }
        // Possibly flush caches that store this userReport here
    }
}
