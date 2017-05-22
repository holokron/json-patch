<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Matcher;

use Holokron\JsonPatch\Patch;

/**
 * @author Michał Tęczyński <michal.teczynski@gmail.com>
 */
interface MatcherInterface
{
    public function match(Patch $patch): array;
}
