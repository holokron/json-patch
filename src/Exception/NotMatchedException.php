<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Exception;

use Holokron\JsonPatch\Operation;

class NotMatchedException extends \RuntimeException
{
    /**
     * @var Operation
     */
    private $operation;

    public function __construct(Operation $operation)
    {
        parent::__construct("Not matched operation at path: {$operation->getPath()}.");
        $this->operation = $operation;
    }

    public function getOperation(): Operation
    {
        return $this->operation;
    }
}