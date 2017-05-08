<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Definition;

class DefinitionsCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var Definition[]
     */
    private $definitions = [];

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->definitions);
    }

    public function count(): int
    {
        return count($this->definitions);
    }

    public function add(string $name, Definition $definition): self
    {
        $this->definitions[$name] = $definition;

        return $this;
    }

    public function addPrefix(string $prefix, array $requirements = []): self
    {
        $prefix = trim(trim($prefix), '/');
        
        if ('' === $prefix) {
            return $this;
        }

        foreach($this->definitions as $definition) {
            $definition
                ->setPath("/$prefix{$route->getPath()}")
                ->addRequirements($requirements)
                ;
        }

        return $this;
    }

    public function all(): array
    {
        return $this->definitions;
    }

    public function get($name)
    {
        return isset($this->definitions[$name]) ? $this->definitions[$name] : null;
    }
}