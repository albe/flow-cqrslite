<?php
namespace Albe\CqrsLite\Query\Controller;

/*
 * This file is part of the Albe.CqrsLite package.
 */

use Albe\CqrsLite\Query\Domain\Repository\UserRepository;
use Neos\Flow\Annotations as Flow;

class UserController extends \Neos\Flow\Mvc\Controller\ActionController
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
