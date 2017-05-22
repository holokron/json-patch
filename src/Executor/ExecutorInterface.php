<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Executor;

/**
 * @author Michał Tęczyński <michal.teczynski@gmail.com>
 */
interface ExecutorInterface
{
    public function execute(callable $callback, array $params = [], $subject = null, $value = null);
}
