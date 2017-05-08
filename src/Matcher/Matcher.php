<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Matcher;

use Holokron\JsonPatch\Operation;
use Holokron\JsonPatch\Definition\DefinitionsCollection;
use Holokron\JsonPatch\Definition\MatchedDefinition;
use Holokron\JsonPatch\Exception\NotMatchedException;

class Matcher implements MatcherInterface
{
    /**
     * @var DefinitionsCollection
     */
    private $definitions;

    public function __construct(DefinitionsCollection $definitions)
    {
        $this->definitions = $definitions;
    }

    /**
     * {@inheritDoc}
     */
    public function match(Operation $operation): MatchedDefinition
    {
        /* @var @definition Definition */
        foreach($this->definitions as $name => $definition) {
            if ($operation->getOp() !== $definition->getOp()) {
                continue;
            }
            $compiledDef = $definition->compile();

            if (!preg_match($compiledDef->getRegex(), $operation->getPath(), $params)) {
                continue;
            }

            array_shift($params);

            return new MatchedDefinition($compiledDef->getCallback(), $params);
        }

        throw new NotMatchedException($operation);
    }
}