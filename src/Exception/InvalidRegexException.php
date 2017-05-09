<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Exception;

class InvalidRegexException extends \RuntimeException
{
    public function __construct(string $regex)
    {
        parent::__construct("Invalid path regex provided in definition: $regex.");
    }
}
