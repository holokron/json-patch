<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Matcher;

use Holokron\JsonPatch\Operation;
use Holokron\JsonPatch\Definition\MatchedDefinition;

interface MatcherInterface
{
    public function match(Operation $operation): MatchedDefinition;
}