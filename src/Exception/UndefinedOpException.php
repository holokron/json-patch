<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Exception;

class UndefinedOpException extends \RuntimeException
{
    public function __construct(string $op)
    {
        parent::__construct("Undefined JSON patch op in operation definition: $op.");
    }
}