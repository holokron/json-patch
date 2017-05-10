<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Definition;

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
    const REQUIREMENT_START = '{';

    /**
     * @var string
     */
    const REQUIREMENT_END = '}';

    /**
     * @var string
     */
    const DEFAULT_REQUIREMENT_REGEX = '[\w\d\-\_\.]+';

    public static function compile(Definition $definition): CompiledDefinition
    {
        $path = $definition->getPath();
        $requirements = $definition->getRequirements();

        return new CompiledDefinition(
            $definition->getOp(),
            static::generateRegex($path, $requirements),
            static::isPathStatic($path),
            $definition->getCallback(),
            static::orderRequirements($path, $requirements)
        );
    }

    private static function orderRequirements(string $path, array $requirements): array
    {
        $orderedKeys = [];
        foreach (array_keys($requirements) as $key) {
            $orderedKeys[$key] = strpos($path, $key);
        }

        asort($orderedKeys);

        $orderedRequirements = [];
        foreach ($orderedKeys as $key => $value) {
            $orderedRequirements[$key] = $requirements[$key];
        }

        return $orderedRequirements;
    }

    private static function generateRegex(string $path, array $requirements): string
    {
        $regexParts = [];
        $tokens = explode(static::DELIMITER, trim($path, '/'));
        foreach ($tokens as $token) {
            if (0 === strpos($token, static::REQUIREMENT_START)) {
                $key = trim($token, '{}');
                $regexParts[] = static::DELIMITER_REGEX;
                $regexParts[] = '(' . (array_key_exists($key, $requirements) ? $requirements[$key] : static::DEFAULT_REQUIREMENT_REGEX) . ')';
            } else {
                $regexParts[] = static::DELIMITER_REGEX . $token;
            }
        }

        return '/^' . implode('', $regexParts) . '$/';
    }

    private static function isPathStatic(string $path): bool
    {
        return false === strpos($path, static::REQUIREMENT_START);
    }
}
