<?php

declare(strict_types=1);

namespace Holokron\JsonPatch;

/**
 * @author Michał Tęczyński <michalv8@gmail.com>
 */
class Patch
{
    /**
     * List of valid operation types according to RFC 6902.
     */
    const ADD = 'add';
    const REMOVE = 'remove';
    const REPLACE = 'replace';
    const MOVE = 'move';
    const COPY = 'copy';
    const TEST = 'test';

    /**
     * @var string
     */
    private $op;

    /**
     * @var string
     */
    private $path;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var null|string
     */
    private $from;

    public function __construct(string $op, string $path, $value = null, $from = null)
    {
        $this->op = $op;
        $this->path = $path;
        $this->value = $value;
        $this->from = $from;
    }

    /**
     * @return string Path of patch
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string Type of operation which will be applied
     */
    public function getOp(): string
    {
        return $this->op;
    }

    /**
     * @return mixed Optional value of patch
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string Path from to copy value to path in case of Patch::COPY operation
     */
    public function getFrom()
    {
        return $this->from;
    }

    public static function create(array $patch): Patch
    {
        return new static(
            $patch['op'],
            $patch['path'],
            $patch['value'] ?? null,
            $patch['from'] ?? null
        );
    }
}
