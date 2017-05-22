<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Parser;

use Holokron\JsonPatch\Exception\ParseException;

/**
 * @author Michał Tęczyński <michal.teczynski@gmail.com>
 */
class JsonDecodeParser implements ParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse(string $json): array
    {
        $data = json_decode($json, true);

        if (null === $data) {
            throw new ParseException(json_last_error_msg());
        }

        return $data;
    }
}
