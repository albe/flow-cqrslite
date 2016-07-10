<?php
namespace Albe\CqrsLite\Command\Controller;

/*
 * This file is part of the Albe.CqrsLite package.
 */

use Albe\CqrsLite\Command\Commands;
use Albe\CqrsLite\Command\Domain\Model\Command;

use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\Flow\Annotations as Flow;

/**
 * A controller for dispatching persisted commands.
 *
 * This is mainly useful for replaying the system in debugging or smoke tests, but can also be used as single
 * dispatch API endpoint. It is by no means required for building a CQRS system.
 */
class CommandController extends ActionController
{
    /**
     * Dispatch a command by convention. The command type is the name of the package key, controller and action to invoke.
     * The format is supposed to look like this:
     *   {packageKey}:{controllerName}.{actionName}
     * 
     * The packageKey and controllerName can be omitted and will lead to dispatching inside the current package/controller.
     *
     * @param Command $command
     * @return string
     */
    public function dispatchAction(Command $command)
    {
        $packageKey = null;
        $controllerName = null;
        $actionName = $command->getType();
        if (strpos($command->getType(), '.') > 0) {
            list($controllerName, $actionName) = explode('.', $command->getType());
            if (strpos($controllerName, ':') > 0) {
                list($packageKey, $controllerName) = explode(':', $controllerName);
                $packageKey = str_replace('.', '\\', $packageKey);
            }
        }

        $actionArguments = array('command' => array_merge($command->getPayload(), array('commandId' => $command->getCommandId())));
        $this->forward(lcfirst($actionName), $controllerName, $packageKey, array_merge($this->request->getInternalArguments(), $actionArguments));
    }

    protected function initializeDispatchAction()
    {
        $this->arguments->getArgument('command')->getPropertyMappingConfiguration()->allowAllProperties();
    }
}