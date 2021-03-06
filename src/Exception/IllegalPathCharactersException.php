<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Exception;

/**
 * @author Michał Tęczyński <michal.teczynski@gmail.com>
 */
class IllegalPathCharactersException extends \RuntimeException
{
    public function __construct(string $path)
    {
        parent::__construct("Illegal path characters in patch path definition: $path.");
    }
}
