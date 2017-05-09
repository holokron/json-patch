<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Executor;

interface ExecutorInterface
{
    public function execute(callable $callback, array $params = [], $subject = null, $value = null);
}
