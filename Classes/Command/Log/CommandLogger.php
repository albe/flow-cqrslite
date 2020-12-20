<?php
namespace Albe\CqrsLite\Command\Log;

/*
 * This file is part of the Albe.CqrsLite package.
 */

use Albe\CqrsLite\Command\Controller\CommandControllerInterface;
use Albe\CqrsLite\Command\Domain\Model\Command;
use Albe\CqrsLite\Command\Domain\Repository\CommandRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Mvc\Controller\ControllerInterface;
use Neos\Flow\Mvc\RequestInterface;
use Neos\Flow\Mvc\ActionResponse;
use Neos\Flow\Security\Context;

/**
 * A Slot that handles logging incoming commands for debugging purposes.
 * It is in essence a better request log, capturing only intentful requests to your system.
 *
 * The command log can be used for finding the requests that lead to some buggy system state (especially with
 * EventSourced Aggregates) or for plain smoke testing a system prior to deployment, by running known commands that
 * have been executed against the current system and verifying they execute sucessfully or lead to the same state
 * if no changes to the structure of your state have been made.
 *
 * This is only a small benefit of using CQRS, but is not required at all for running the system.
 * @Flow\Scope("singleton")
 */
class CommandLogger
{
    /**
     * @var CommandRepository
     * @Flow\Inject(lazy=true)
     */
    protected $commandLog;

    /**
     * @var Context
     * @Flow\Inject(lazy=true)
     */
    protected $securityContext;

    /**
     * Log the command that is sent to a CommandController
     *
     * @param RequestInterface $request
     * @param ActionResponse $response
     * @param ControllerInterface $controller
     * @throws \Neos\Flow\Persistence\Exception\IllegalObjectTypeException
     */
    public function onBeforeControllerInvocation(RequestInterface $request, ActionResponse $response, ControllerInterface $controller)
    {
        if ($controller instanceof CommandControllerInterface || strpos($request->getControllerObjectName(), '\\Command\\') !== false) {
            if ($request instanceof ActionRequest) {
                $commandNamespace = $request->getControllerPackageKey() . ($request->getControllerSubpackageKey() ? '.' . $request->getControllerSubpackageKey() : '');
                $commandType = str_replace('\\', '.', $commandNamespace) . ':' . $request->getControllerName() . '.' . $request->getControllerActionName();

                $account = $this->securityContext->getAccount();

                $command = new Command($commandType, $request->getHttpRequest()->getParsedBody('command'), array('issuedBy' => $account ? $account->getAccountIdentifier() : null, 'issuedFrom' => $request->getHttpRequest()->getAttribute('clientIpAddress')));
                $this->commandLog->add($command);
            }
        }
    }
}
