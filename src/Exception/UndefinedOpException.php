<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Exception;

/**
 * @author Michał Tęczyński <michal.teczynski@gmail.com>
 */
class UndefinedOpException extends \RuntimeException
{
    public function __construct(string $op)
    {
        parent::__construct("Undefined JSON patch op in operation definition: $op.");
    }
}
