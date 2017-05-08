<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Parser;

class JsonDecodeParse implements ParserInterface
{
    public function parse(string $json): array
    {
        $data = json_decode($json, true);
        
        if (null === $data) {
            throw new ParseException(json_last_error_msg());
        }

        return $data;
    }
}