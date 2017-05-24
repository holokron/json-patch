<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Definition;

/**
 * @author Michał Tęczyński <michal.teczynski@gmail.com>
 */
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
     * @var callable
     */
    private $callback;

    /**
     * @var array
     */
    private $requirements;

    public function __construct(string $op, string $regex, callable $callback, array $requirements = [])
    {
        $this->op = $op;
        $this->regex = $regex;
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

    public function getCallback(): callable
    {
        return $this->callback;
    }

    public function getRequirements(): array
    {
        return $this->requirements;
    }
}
