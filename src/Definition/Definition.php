<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Definition;

use Holokron\JsonPatch\Exception\IllegalPathCharactersException;
use Holokron\JsonPatch\Exception\InvalidRegexException;
use Holokron\JsonPatch\Exception\InvalidRequirementException;
use Holokron\JsonPatch\Exception\UndefinedOpException;

/**
 * @author Michał Tęczyński <michal.teczynski@gmail.com>
 */
class Definition
{
    const PATH_REQUIREMENT_ALLOWED_CHARACTERS = '[\d\w\_]';

    /**
     * @var string
     */
    private $op;

    /**
     * @var string
     */
    private $path = '/';

    /**
     * @var string[]
     */
    private $requirements = [];

    /**
     * @var callable
     */
    private $callback;

    /**
     * @var null|CompiledDefinition
     */
    private $compiled = null;

    public function __construct(string $op, string $path, callable $callback)
    {
        static::validate($op, $path);
        $this->op = $op;
        $this->setPath($path);
        $this->callback = $callback;
    }

    public function getOp(): string
    {
        return $this->op;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = '/' . ltrim(trim($path), '/');
        $this->compiled = null;

        return $this;
    }

    public function getRequirements(): array
    {
        return $this->requirements;
    }

    public function setRequirements(array $requirements): self
    {
        $this->requirements = [];

        return $this->addRequirements($requirements);
    }

    public function getCallback(): callable
    {
        return $this->callback;
    }

    public function addRequirements(array $requirements): self
    {
        foreach ($requirements as $key => $regex) {
            if (is_numeric($key[0])) {
                throw new InvalidRequirementException('Beginning of requirement name cannot be integer.');
            }

            if (!preg_match('/^' . static::PATH_REQUIREMENT_ALLOWED_CHARACTERS . '+$/', $key)) {
                throw new InvalidRequirementException('Requirement name consist illegal characters.');
            }

            $this->requirements[$key] = static::sanitizeRequirement($regex);
        }
        $this->compiled = null;

        return $this;
    }

    public function compile(): CompiledDefinition
    {
        if (null === $this->compiled) {
            $this->compiled = Compiler::compile($this);
        }

        return $this->compiled;
    }

    private static function sanitizeRequirement(string $regex): string
    {
        $sanitized = trim($regex, '^$');
        if ('' === $sanitized) {
            throw new InvalidRegexException($regex);
        }

        return $sanitized;
    }

    private static function validate(string $op, string $path)
    {
        if (!in_array($op, ['add', 'remove', 'replace', 'move', 'copy', 'test'], true)) {
            throw new UndefinedOpException($op);
        }

        if (preg_match('/[^\d\w\/\-\.\:]/', $path)) {
            throw new IllegalPathCharactersException($path);
        }
    }
}
