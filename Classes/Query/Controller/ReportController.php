<?php
namespace Albe\CqrsLite\Query\Controller;

/*
 * This file is part of the Albe.CqrsLite package.
 */

use Albe\CqrsLite\Query\Domain\Repository\UserReportRepository;
use Neos\Flow\Annotations as Flow;

class ReportController extends \Neos\Flow\Mvc\Controller\ActionController
{
    /**
     * @var UserReportRepository
     * @Flow\Inject
     */
    protected $userReports;

    /**
     * @return void
     */
    public function usersAction()
    {
        $this->view->assign('reports', $this->userReports->findAll());
    }
}
