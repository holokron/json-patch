<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Definition;

class CompiledDefinition 
{
    /**
     * @var string
     */
    private $op;

    /**
     * @var string
     */
    private $regex;

    /**
     * @var string
     */
    private $staticPrefix;

    /**
     * @var callable
     */
    private $callback;

    /**
     * @var array
     */
    private $requirements = [];

    public function __construct(string $op, string $regex, string $staticPrefix, callable $callback, array $requirements = [])
    {
        $this->op = $op;
        $this->regex = $regex;
        $this->staticPrefix = $staticPrefix;
        $this->callback = $callback;
        $this->requirements = $requirements;
    }

    public function getOp(): string
    {
        return $this->op;
    }    

    public function getRegex(): string
    {
        return $this->regex;
    }

    public function getStaticPrefix(): string
    {
        return $this->staticPrefix;
    }

    public function getCallback(): callable
    {
        return $this->callback;
    }

    public function getRequirements(): array
    {
        return $this->requirements;
    }
}