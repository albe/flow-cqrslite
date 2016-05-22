<?php
namespace Albe\CqrsLite\Query\Controller;

/*
 * This file is part of the Albe.CqrsLite package.
 */

use Albe\CqrsLite\Query\Domain\Repository\UserRepository;
use TYPO3\Flow\Annotations as Flow;

class UserController extends \TYPO3\Flow\Mvc\Controller\ActionController
{
    /**
     * @var UserRepository
     * @Flow\Inject
     */
    protected $users;

    public function newAction()
    {
    }

    /**
     * @return void
     */
    public function listAction()
    {
        $this->view->assign('users', $this->users->findAll());
    }
}
