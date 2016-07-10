<?php
namespace Albe\CqrsLite\Query\Domain\Model;

/*
 * This file is part of the Albe.CqrsLite package.
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * This is an example of read-side only model. It captures additional information about what happened
 * on the write side, that is not essential for business rules.
 *
 * @Flow\Entity
 * @ORM\Table(indexes={@ORM\Index(name="type_idx", columns={"type"})})
 */
class UserReport
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $count;

    /**
     * UserReport constructor.
     * @param string $type
     * @param int $count
     */
    public function __construct($type, $count = 1)
    {
        $this->type = $type;
        $this->count = $count;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $amount
     */
    public function increaseCount($amount = 1)
    {
        $this->count += (int) $amount;
    }

    /**
     * @param int $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }
}
