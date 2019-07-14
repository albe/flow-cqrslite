<?php
namespace Albe\CqrsLite;

/*
 * This file is part of the Albe.CqrsLite package.
 */

use Albe\CqrsLite\Command\Controller\UserController;
use Albe\CqrsLite\Command\Log\CommandLogger;
use Albe\CqrsLite\Query\Service\UserHandler;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Dispatcher;
use Neos\Flow\Package\Package as BasePackage;
use Albe\CqrsLite\Query\Service\UserReportHandler;

/**
 * @Flow\Proxy(false)
 */
class Package extends BasePackage
{
    /**
     * Boot the package. We wire some signals to slots here.
     *
     * @param \Neos\Flow\Core\Bootstrap $bootstrap The current bootstrap
     * @return void
     */
    public function boot(\Neos\Flow\Core\Bootstrap $bootstrap)
    {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();

        // Connect the command logger to receive any commands coming in
        $dispatcher->connect(
            Dispatcher::class, 'beforeControllerInvocation',
            CommandLogger::class, 'onBeforeControllerInvocation'
        );

        // Connect the read-side handlers to the write-side signals
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
