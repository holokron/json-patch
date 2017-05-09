<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Exception;

use Holokron\JsonPatch\Patch;

class NotMatchedException extends \RuntimeException
{
    /**
     * @var Patch
     */
    private $patch;

    public function __construct(Patch $patch)
    {
        parent::__construct("Not matched operation at path: {$patch->getPath()}.");
        $this->patch = $patch;
    }

    public function getPatch(): Patch
    {
        return $this->patch;
    }
}
