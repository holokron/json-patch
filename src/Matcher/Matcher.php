<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Matcher;

use Holokron\JsonPatch\Definition\DefinitionsCollection;
use Holokron\JsonPatch\Exception\NotMatchedException;
use Holokron\JsonPatch\Patch;

/**
 * @author Michał Tęczyński <michal.teczynski@gmail.com>
 */
class Matcher implements MatcherInterface
{
    /**
     * @var DefinitionsCollection
     */
    private $definitions;

    public function __construct(DefinitionsCollection $definitions = null)
    {
        $this->definitions = $definitions ?: new DefinitionsCollection();
    }

    public function setDefinitions(DefinitionsCollection $definitions): self
    {
        $this->definitions = $definitions;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function match(Patch $patch): array
    {
        /* @var @definition Definition */
        foreach ($this->definitions as $name => $definition) {
            if ($patch->getOp() !== $definition->getOp()) {
                continue;
            }
            $compiledDef = $definition->compile();
            if (!preg_match($compiledDef->getRegex(), $patch->getPath(), $params)) {
                continue;
            }

            array_shift($params);

            return [$compiledDef->getCallback(), $params];
        }

        throw new NotMatchedException($patch);
    }
}
