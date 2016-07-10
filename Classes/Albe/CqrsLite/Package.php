<?php
namespace Albe\CqrsLite;

/*
 * This file is part of the Albe.CqrsLite package.
 */

use Albe\CqrsLite\Command\Controller\UserController;
use Albe\CqrsLite\Command\Log\CommandLogger;
use Albe\CqrsLite\Query\Service\UserHandler;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Dispatcher;
use TYPO3\Flow\Package\Package as BasePackage;
use Albe\CqrsLite\Command\Controller\StandardController;
use Albe\CqrsLite\Query\Service\UserReportHandler;

/**
 * @Flow\Proxy(false)
 */
class Package extends BasePackage
{
    /**
     * Boot the package. We wire some signals to slots here.
     *
     * @param \TYPO3\Flow\Core\Bootstrap $bootstrap The current bootstrap
     * @return void
     */
    public function boot(\TYPO3\Flow\Core\Bootstrap $bootstrap)
    {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();

        $dispatcher->connect(
            Dispatcher::class, 'beforeControllerInvocation',
            CommandLogger::class, 'onBeforeControllerInvocation'
        );

        $dispatcher->connect(
            UserController::class, 'userRegistered',
            UserHandler::class, 'onUserRegistered'
        );
        $dispatcher->connect(
            UserController::class, 'userRegistered',
            UserReportHandler::class, 'onUserRegistered'
        );
    }
}
