<?php
namespace Albe\CqrsLite\Command\Controller;

/*
 * This file is part of the Albe.CqrsLite package.
 */

use Albe\CqrsLite\Command\Commands;

use Albe\CqrsLite\Command\Domain\Model\User;
use Albe\CqrsLite\Command\Domain\Repository\UserRepository;

use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\Flow\Annotations as Flow;

/**
 * A sample write-side Controller which is responsible for handling all commands dealing with a User.
 * In most cases you will end up with one controller for every of your write-side Aggregates.
 *
 * It is no problem to create a Controller that handles a long running business process though, that spans
 * multiple Aggregates inside a single transaction. You should generally try to avoid these, as they are
 * counter-intuitive to the transactional nature of Aggregates, but they can not always be avoided.
 */
class UserController extends ActionController implements CommandControllerInterface
{
    /**
     * @var UserRepository
     * @Flow\Inject
     */
    protected $users;

    /**
     * A sample action, which functions as a CommandHandler. This is the most simple way to handle commands,
     * since the Flow Dispatcher can be used as a simple Command Dispatcher.
     * 
     * The request URI specifies the expected command type to be executed, the Controller name is the namespace
     * for the command and the action name is the actual command name.
     * The action argument is actually just a DTO to gain from automatic validation and giving structure and type
     * safety to the command payload.
     *
     * We use a custom Validator for making sure the business rule "username must be unique" holds.
     *
     * @param Commands\RegisterUser $command
     * @Flow\Validate(argumentName="command",type="Albe.CqrsLite.Command:UniqueUserName")
     */
    public function registerAction(Commands\RegisterUser $command)
    {
        // Notice how we only use the username from the Command payload for the write-side.
        // The write side should only deal with models that are necessary to keep business rules intact,
        // in this case that the username must be unique.
        $user = new User($command->getUsername());
        $this->users->add($user);

        // Use a Signal to do Write -> Read communication. For async behaviour you would use an Eventbus.
        // We only use a plain array for transporting the actual payload, but in a more complex scenario
        // (and especially when using EventSourcing) this could be an Event object which even has some different
        // fields than the incoming Command. Those could be calculated from the Command payload according to some
        // other business rules.
        $this->emitUserRegistered(array('username' => $command->getUsername(), 'type' => $command->getType()));

        $this->forwardToReferringRequest();
    }

    /**
     * @Flow\Signal
     * @param array $user
     * @return void
     */
    public function emitUserRegistered(array $user)
    {
    }
}