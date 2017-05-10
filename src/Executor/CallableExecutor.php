<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Executor;

/**
 * @author Michał Tęczyński <michalv8@gmail.com>
 */
class CallableExecutor implements ExecutorInterface
{
    public function execute(callable $callback, array $params = [], $subject = null, $value = null)
    {
        if (null !== $subject) {
            array_unshift($params, $subject);
        }

        if (null !== $value) {
            $params[] = $value;
        }

        return call_user_func_array($callback, $params);
    }
}
