<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Executor;

/**
 * @author Michał Tęczyński <michalv8@gmail.com>
 */
interface ExecutorInterface
{
    public function execute(callable $callback, array $params = [], $subject = null, $value = null);
}
