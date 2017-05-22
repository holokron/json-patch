<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Definition;

/**
 * @author Michał Tęczyński <michal.teczynski@gmail.com>
 */
class Compiler
{
    /**
     * @var string
     */
    const DELIMITER = '/';

    /**
     * @var string
     */
    const DELIMITER_REGEX = '\\/';

    /**
     * @var string
     */
    const REQUIREMENT_START = ':';

    /**
     * @var string
     */
    const DEFAULT_REQUIREMENT_REGEX = '[\w\d\-\_\.]+';

    public static function compile(Definition $definition): CompiledDefinition
    {
        $path = $definition->getPath();
        $requirements = $definition->getRequirements();

        $regexParts = [];
        $compiledRequirements = [];
        $tokens = explode(static::DELIMITER, trim($path, '/'));
        foreach ($tokens as $token) {
            if (0 === strpos($token, static::REQUIREMENT_START)) {
                $key = trim($token, ': ');
                $requirementRegex = (array_key_exists($key, $requirements) ? $requirements[$key] : static::DEFAULT_REQUIREMENT_REGEX);
                $compiledRequirements[$key] = $requirementRegex;
                $regexParts[] = static::DELIMITER_REGEX . '(' . $requirementRegex . ')';
            } else {
                $regexParts[] = static::DELIMITER_REGEX . $token;
            }
        }

        $regex = '/^' . implode('', $regexParts) . '$/';

        return new CompiledDefinition(
            $definition->getOp(),
            $regex,
            $definition->getCallback(),
            $compiledRequirements
        );
    }
}
