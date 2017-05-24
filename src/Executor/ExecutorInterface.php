<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Executor;

/**
 * @author Michał Tęczyński <michal.teczynski@gmail.com>
 */
interface ExecutorInterface
{
    /**
     * @return mixed
     */
    public function execute(callable $callback, array $params = [], $subject = null, $value = null);
}
