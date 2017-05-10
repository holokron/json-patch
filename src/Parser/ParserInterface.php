<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Parser;

use Holokron\JsonPatch\Exception\ParseException;

/**
 * @author Michał Tęczyński <michalv8@gmail.com>
 */
interface ParserInterface
{
    /**
     * @throws ParseException
     */
    public function parse(string $json): array;
}
