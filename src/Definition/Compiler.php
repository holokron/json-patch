<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Definition;

class Compiler
{
    const PATH_DELIMITER = '/';

    const PATH_DELIMITER_REGEX = '\\/';

    const PATH_REQUIREMENT_START = '{';

    const PATH_REQUIREMENT_END = '}';

    public static function compile(Definition $definition): CompiledDefinition
    {
        $path = $definition->getPath();
        $requirements = static::orderRequirements(
            $path,
            $definition->getRequirements()
        );

        $search = array_merge(
            [static::PATH_DELIMITER],
            array_map(
                function (string $key) {
                    return static::PATH_REQUIREMENT_START . $key . static::PATH_REQUIREMENT_END;
                },
                array_keys($requirements)
            )
        );
        $replace = array_merge(
            [static::PATH_DELIMITER_REGEX],
            array_map(
                function (string $value) {
                    return "($value)";
                },
                array_values($requirements)
            )
        );

        $regex = '/^' . str_replace($search, $replace, $path) . '$/';

        return new CompiledDefinition(
            $definition->getOp(),
            $regex,
            static::isPathStatic($path),
            $definition->getCallback(),
            $requirements
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

    private static function isPathStatic(string $path): bool
    {
        return false === strpos($path, static::PATH_REQUIREMENT_START);
    }
}
