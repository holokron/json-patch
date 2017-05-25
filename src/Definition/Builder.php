<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Definition;

/**
 * @author Michał Tęczyński <michal.teczynski@gmail.com>
 */
class Builder
{
    /**
     * @var string|null
     */
    private $op;

    /**
     * @var string|null
     */
    private $path;

    /**
     * @var string[]
     */
    private $requirements = [];

    /**
     * @var callable|null
     */
    private $callback;

    /**
     * @var DefinitionsCollection
     */
    private $definitions;

    public function __construct()
    {
        $this->definitions = new DefinitionsCollection();
    }

    public function op(string $op): self
    {
        $this->op = $op;

        return $this;
    }

    public function path(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function callback(callable $callback): self
    {
        $this->callback = $callback;

        return $this;
    }

    public function requirement(string $key, string $regex): self
    {
        if (!array_key_exists($key, $this->requirements)) {
            $this->requirements[$key] = $regex;
        }

        return $this;
    }

    public function build(): Definition
    {
        $definition = (new Definition($this->op, $this->path, $this->callback))
            ->addRequirements($this->requirements);
        $this->clear();

        return $definition;
    }

    public function get(): DefinitionsCollection
    {
        $definitions = clone $this->definitions;
        $this->definitions = new DefinitionsCollection();

        return $definitions;
    }

    public function add(string $name = null): self
    {
        if (!$name) {
            $name = trim(str_replace(['/', '{', '}'], ['_', '', ''], "{$this->op}__{$this->path}"), '_/');
        }

        $this->definitions->add($name, $this->build());

        return $this;
    }

    private function clear()
    {
        $this->op = null;
        $this->path = null;
        $this->callback = null;
        $this->requirements = [];
    }
}
