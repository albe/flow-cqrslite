<?php
namespace Albe\CqrsLite\Command\Domain\Model;

/*
 * This file is part of the Albe.CqrsLite package.
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Utility\Algorithms;

/**
 * A persisted command used for the command log for debugging and smoke testing systems.
 *
 * @Flow\Entity(readOnly=true)
 * @ORM\Table(indexes={@ORM\Index(name="type_idx", columns={"type", "issuedOn"})})
 */
class Command
{
    /**
     * @var string
     * @ORM\Id
     */
    protected $commandId;

    /**
     * @var string
     * @Flow\Validate(type="NotEmpty")
     */
    protected $type;

    /**
     * Identifier of the account that issued the command.
     * @var string
     * @ORM\Column(nullable=true)
     */
    protected $issuedBy;

    /**
     * IP that issued the command.
     * @var string
     */
    protected $issuedFrom;

    /**
     * The date and time when the command was issued.
     * @var \DateTime
     */
    protected $issuedOn;

    // Add other denormalized meta information fields as required for querying commands

    /**
     * Any other meta information.
     * @var array
     * @ORM\Column(type="json_array")
     */
    protected $meta;

    /**
     * @var array
     * @ORM\Column(type="json_array")
     */
    protected $payload;

    /**
     * Command constructor.
     * @param string $type The command type
     * @param array $payload The command payload
     * @param array $meta Any meta data to store with the command for debugging
     */
    public function __construct($type, array $payload = array(), array $meta = array())
    {
        $this->commandId = Algorithms::generateUUID();
        $this->type = $type;
        $this->payload = $payload;
        if (isset($meta['issuedBy'])) {
            $this->issuedBy = $meta['issuedBy'];
        }
        unset($meta['issuedBy']);
        if (isset($meta['issuedFrom'])) {
            $this->issuedFrom = $meta['issuedFrom'];
        }
        unset($meta['issuedFrom']);
        if (isset($meta['issuedOn'])) {
            if ($meta['issuedOn'] instanceof \DateTime) {
                $this->issuedOn = clone $meta['issuedOn'];
            } else {
                $this->issuedOn = new \DateTime($meta['issuedOn']);
            }
        }
        unset($meta['issuedOn']);
        if ($this->issuedOn === null) {
            $this->issuedOn = new \DateTime();
        }
        $this->meta = $meta;
    }

    /**
     * @return string
     */
    public function getCommandId()
    {
        return $this->commandId;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getIssuedBy()
    {
        return $this->issuedBy;
    }

    /**
     * @return \DateTime
     */
    public function getIssuedOn()
    {
        return clone $this->issuedOn;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param array $meta
     * @return Command
     */
    public function withMeta(array $meta)
    {
        return new Command($this->type, $this->payload, array_merge(array('issuedBy' => $this->issuedBy, 'issuedFrom' => $this->issuedFrom, 'issuedOn' => $this->issuedOn), $this->meta, $meta));
    }
}
