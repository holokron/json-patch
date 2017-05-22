<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Parser;

use Holokron\JsonPatch\Exception\ParseException;

/**
 * @author Michał Tęczyński <michal.teczynski@gmail.com>
 */
interface ParserInterface
{
    /**
     * @throws ParseException
     */
    public function parse(string $json): array;
}
