<?php
namespace Albe\CqrsLite\Command\Controller;

/*
 * This file is part of the Albe.CqrsLite package.
 */

use Albe\CqrsLite\Command\Commands;
use Albe\CqrsLite\Command\Domain\Model\Command;
use Albe\CqrsLite\Command\Domain\Repository\CommandRepository;

use Albe\CqrsLite\Query\Domain\Model\User;
use Albe\CqrsLite\Query\Domain\Repository\UserRepository;

use TYPO3\Flow\Annotations as Flow;

class StandardController extends \TYPO3\Flow\Mvc\Controller\ActionController
{
    /**
     * @var UserRepository
     * @Flow\Inject
     */
    protected $users;

    /**
     * @var CommandRepository
     * @Flow\Inject
     */
    protected $commandLog;

    /**
     * Dispatch a command by convention. The command type is the name of the package key, controller and action to invoke.
     * The format is supposed to look like this:
     *   {packageKey}:{controllerName}.{actionName}
     * 
     * The packageKey and controllerName can be omitted and will lead to dispatching inside the current package/controller.
     *
     * @param Command $command
     * @return string
     * TODO: This should be an own HttpComponent
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
        // This is actually highly optional and mainly useful for debugging purposes
        $this->commandLog->add($command->withMeta(array('issuedBy' => $this->request->getHttpRequest()->getClientIpAddress())));

        $actionArguments = array('command' => $command->getPayload(), 'commandId' => $this->persistenceManager->getIdentifierByObject($command));
        $this->forward(lcfirst($actionName), $controllerName, $packageKey, array_merge($this->request->getInternalArguments(), $actionArguments));
    }

    protected function initializeAction()
    {
        $this->arguments->getArgument('command')->getPropertyMappingConfiguration()->allowAllProperties();
    }

    /**
     * @param Commands\CreateUser $command
     * @Flow\Validate(argumentName="command",type="Albe.CqrsLite.Command:UniqueUserName")
     */
    public function createUserAction(Commands\CreateUser $command)
    {
        // Do whatever is necessary to create a new user with the given properties
        $user = new User($command->getUsername(), $command->getType());
        $this->users->add($user);

        $this->emitUserCreated($user);
        $this->forwardToReferringRequest();
    }

    /**
     * @Flow\Signal
     * @param User $user
     * @return void
     */
    public function emitUserCreated(User $user)
    {
    }
}