<?php

declare(strict_types=1);

namespace Holokron\JsonPatch;

use Holokron\JsonPatch\Exception\InvalidPatchException;

/**
 * @author Michał Tęczyński <michal.teczynski@gmail.com>
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

    const OP = [
        self::ADD,
        self::REMOVE,
        self::REPLACE,
        self::MOVE,
        self::COPY,
        self::TEST,
    ];

    const PROPERTIES = [
        self::ADD => ['op', 'path', 'value'],
        self::REMOVE => ['op', 'path'],
        self::REPLACE => ['op', 'path', 'value'],
        self::MOVE => ['op', 'path', 'from'],
        self::COPY => ['op', 'path', 'from'],
        self::TEST => ['op', 'path', 'value'],
    ];

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

    public function __construct(array $patch)
    {
        static::validate($patch);
        foreach ($patch as $key => $value) {
            $this->$key = $value;
        }
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

    private static function validate(array $patch)
    {
        if (!isset($patch['op']) || !in_array($patch['op'], static::OP, true)) {
            throw new InvalidPatchException($patch);
        }

        if (static::PROPERTIES[$patch['op']] !== array_intersect(static::PROPERTIES[$patch['op']], array_keys($patch))) {
            throw new InvalidPatchException($patch);
        }
    }

    public static function create(array $patch): Patch
    {
        return new static($patch);
    }
}
