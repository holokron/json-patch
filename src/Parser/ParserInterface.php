<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Parser;

interface ParserInterface
{
    public function parse(string $json): array;
}