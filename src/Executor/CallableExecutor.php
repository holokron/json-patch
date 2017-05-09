<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Executor;

class CallableExecutor implements ExecutorInterface
{
    public function execute(callable $callback, array $params = [], $subject = null, $value = null)
    {
        if (null !== $value) {
            array_unshift($params, $value);
        }

        if (null !== $subject) {
            array_unshift($params, $subject);
        }

        return call_user_func_array($callback, $params);
    }
}
