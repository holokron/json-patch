<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Definition;

class MatchedDefinition
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @var array
     */
    private $params;

    public function __construct(callable $callback, array $params = [])
    {
        $this->callback = $callback;
        $this->params = $params;
    }

    public function getCallback(): callable
    {
        return $this->callback;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}