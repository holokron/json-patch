<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Matcher;

use Holokron\JsonPatch\Exception\NotMatchedException;
use Holokron\JsonPatch\Patch;

/**
 * @author Michał Tęczyński <michal.teczynski@gmail.com>
 */
interface MatcherInterface
{
    /**
     * @throws NotMatchedException
     */
    public function match(Patch $patch): array;
}
