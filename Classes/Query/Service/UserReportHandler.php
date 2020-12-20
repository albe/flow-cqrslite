<?php
namespace Albe\CqrsLite\Query\Service;

/*
 * This file is part of the Albe.CqrsLite package.
 */

use Neos\Flow\Annotations as Flow;
use Albe\CqrsLite\Query\Domain\Model\UserReport;
use Albe\CqrsLite\Query\Domain\Repository\UserReportRepository;

/**
 * An event handler implemented as Slot, that takes care of building a read model that holds the number
 * of registered user aggregated by their type.
 *
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
     * @var \Psr\Log\LoggerInterface
     * @Flow\Inject
     */
    protected $systemLogger;

    /**
     * @param array $user
     * @return void
     */
    public function onUserRegistered(array $user)
    {
        $this->systemLogger->info('onUserRegistered');
        /* @var $userReport UserReport */
        $userReport = $this->userReports->findOneByType($user['type']);
        if (!$userReport) {
            $userReport = new UserReport($user['type']);
            $this->userReports->add($userReport);
        } else {
            $userReport->increaseCount();
            $this->userReports->update($userReport);
        }
        // Possibly flush caches that store this userReport here
    }
}
