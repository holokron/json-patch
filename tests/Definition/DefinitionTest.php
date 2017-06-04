<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Tests\Definition;

use Holokron\JsonPatch\Definition\Definition;
use PHPUnit\Framework\TestCase;

class DefinitionTest extends TestCase
{
    /**
     * @dataProvider dataDefinitionAndCompile
     */
    public function testDefinitionAndCompile(
        array $args,
        array $requirements = [],
        string $expectedOp,
        string $expectedPath,
        callable $expectedCallback,
        array $expectedRequirements = []
    ) {
        $definition = new Definition(...$args);
        $definition->setRequirements($requirements);

        $this->assertSame($expectedOp, $definition->getOp());
        $this->assertSame($expectedPath, $definition->getPath());
        $this->assertSame($expectedCallback, $definition->getCallback());
        $this->assertSame($expectedRequirements, $definition->getRequirements());

        $compiled = $definition->compile();

        $this->assertSame($definition->getOp(), $compiled->getOp());
        $this->assertSame($definition->getCallback(), $compiled->getCallback());
    }

    public static function dataDefinitionAndCompile(): array
    {
        $callback = function () {};

        return [
            [
                ['add', '/users', $callback],
                [],
                'add',
                '/users',
                $callback,
                [],
            ],
            [
                ['remove', '/users/:userId', $callback],
                [
                    'userId' => '\d+',
                ],
                'remove',
                '/users/:userId',
                $callback,
                [
                    'userId' => '\d+',
                ],
            ],
            [
                ['replace', '/users/:userId/posts', $callback],
                [
                    'userId' => '\w+',
                ],
                'replace',
                '/users/:userId/posts',
                $callback,
                [
                    'userId' => '\w+',
                ],
            ],
            [
                ['move', '/users/:userId/posts/:postId', $callback],
                [
                    'userId' => '\w+',
                    'postId' => '\d+',
                ],
                'move',
                '/users/:userId/posts/:postId',
                $callback,
                [
                    'userId' => '\w+',
                    'postId' => '\d+',
                ],
            ],
            [
                ['copy', '/users/:userId/posts/:postId/comments', $callback],
                [
                    'userId' => '\w+',
                    'postId' => '\d+',
                ],
                'copy',
                '/users/:userId/posts/:postId/comments',
                $callback,
                [
                    'userId' => '\w+',
                    'postId' => '\d+',
                ],
            ],
            [
                ['test', '/users/:userId/posts/:postId/comments/:commentId', $callback],
                [
                    'userId' => '\w+',
                    'postId' => '\d+',
                    'commentId' => '[\d\w]+',
                ],
                'test',
                '/users/:userId/posts/:postId/comments/:commentId',
                $callback,
                [
                    'userId' => '\w+',
                    'postId' => '\d+',
                    'commentId' => '[\d\w]+',
                ],
            ],
        ];
    }

    /**
     * @dataProvider      dataDefinitionWhenOpIsInvalid
     * @expectedException \Holokron\JsonPatch\Exception\UndefinedOpException
     */
    public function testDefinitionWhenOpIsInvalid(string $op, string $path, callable $callback)
    {
        $definition = new Definition($op, $path, $callback);
    }

    public static function dataDefinitionWhenOpIsInvalid(): array
    {
        $callback = function () {};

        return [
            ['create', '/users', $callback],
            ['delete', '/users/:userId', $callback],
            ['edit', '/users/:userId/posts', $callback],
            ['advance', '/users/:userId/posts/:postId', $callback],
            ['duplicate', '/users/:userId/posts/:postId/comments', $callback],
            ['try', '/users/:userId/posts/:postId/comments/:commentId', $callback],
        ];
    }

    /**
     * @dataProvider      dataDefinitionWhenPathHasIllegalCharacters
     * @expectedException \Holokron\JsonPatch\Exception\IllegalPathCharactersException
     */
    public function testDefinitionWhenPathHasIllegalCharacters(string $op, string $path, callable $callback)
    {
        $definition = new Definition($op, $path, $callback);
    }

    public static function dataDefinitionWhenPathHasIllegalCharacters(): array
    {
        $callback = function () {};

        return [
            ['add', '/users?', $callback],
            ['remove', '/users/{userId}', $callback],
            ['replace', '/users/<userId>/posts', $callback],
            ['move', '%users%:userId%posts%:postId', $callback],
            ['copy', '|users|:userId|posts|:postId|comments', $callback],
            ['test', '|users,:userId&posts):postId"comments;:commentId', $callback],
        ];
    }

    /**
     * @dataProvider dataDefinitionWhenRequirementIsInvalid
     * @expectedException \Holokron\JsonPatch\Exception\InvalidRequirementException
     */
    public function testDefinitionWhenRequirementIsInvalid(array $requirements)
    {
        $definition = new Definition('add', '/foo/bar', function() {});
        $definition->addRequirements($requirements);
    }

    public static function dataDefinitionWhenRequirementIsInvalid(): array
    {
        return [
            [
                [
                    '123test' => '\d+'
                ]
            ],
            [
                [
                    '{test%' => '\w+',
                ]
            ],
            [
                [
                    '\foo\\bar\\123' => '',
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataDefinitionWhenInvalidRegexInRequirementIsGiven
     * @expectedException \Holokron\JsonPatch\Exception\InvalidRegexException
     */
    public function testDefinitionWhenInvalidRegexInRequirementIsGiven(string $regex)
    {
        $definition = new Definition('add', '/foo/bar', function() {});
        $definition->addRequirements([
            'foo' => $regex,
        ]);
    }

    public static function dataDefinitionWhenInvalidRegexInRequirementIsGiven(): array
    {
        return [
            [''],
            ['^'],
            ['$'],
            ['^$'],
        ];
    }
}
