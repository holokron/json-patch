<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Matcher;

use Holokron\JsonPatch\Definition\MatchedDefinition;
use Holokron\JsonPatch\Patch;

interface MatcherInterface
{
    public function match(Patch $patch): MatchedDefinition;
}
