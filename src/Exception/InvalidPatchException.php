<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Exception;

/**
 * @author Michał Tęczyński <michal.teczynski@gmail.com>
 */
class InvalidPatchException extends \RuntimeException
{
    /**
     * @var array
     */
    private $patch;

    public function __construct(array $patch)
    {
        parent::__construct('Invalid patch.');
        $this->patch = $patch;
    }

    public function getPatch(): array
    {
        return $this->patch;
    }
}
