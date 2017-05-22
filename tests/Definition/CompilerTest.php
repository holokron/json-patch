<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Tests\Definition;

use Holokron\JsonPatch\Definition\Compiler;
use Holokron\JsonPatch\Definition\Definition;
use Holokron\JsonPatch\Tests\Traits\MockTrait;
use PHPUnit\Framework\TestCase;

/**
 * @author Michał Tęczyński <michal.teczynski@gmail.com>
 */
class CompilerTest extends TestCase
{
    use MockTrait;

    /**
     * @dataProvider dataCompile
     */
    public function testCompile(string $path, array $requirements, string $expectedRegex, array $expectedRequirements = [])
    {
        $def = $this->getMockForClass(Definition::class);
        $op = 'add';
        $callback = function () {};
        $this
            ->mockMethod($def, 'getPath', $this->once(), [], $path)
            ->mockMethod($def, 'getRequirements', $this->once(), [], $requirements)
            ->mockMethod($def, 'getOp', $this->once(), [], 'add')
            ->mockMethod($def, 'getCallback', $this->once(), [], $callback);
        $compiled = Compiler::compile($def);
        $this->assertSame($op, $compiled->getOp());
        $this->assertSame($callback, $compiled->getCallback());
        $this->assertSame($expectedRegex, $compiled->getRegex());
        $this->assertSame($expectedRequirements, $compiled->getRequirements());
    }

    public static function dataCompile(): array
    {
        return [
            [
                '/',
                [],
                '/^\/$/',
            ],
            [
                '/foo/bar',
                [],
                '/^\/foo\/bar$/',
            ],
            [
                '/foo/:exampleId',
                [],
                '/^\/foo\/([\w\d\-\_\.]+)$/',
                [
                    'exampleId' => '[\w\d\-\_\.]+',
                ],
            ],
            [
                '/foo/:exampleId/bar',
                [],
                '/^\/foo\/([\w\d\-\_\.]+)\/bar$/',
                [
                    'exampleId' => '[\w\d\-\_\.]+',
                ],
            ],
            [
                '/foo/:exampleId/bar',
                [
                    'exampleId' => '[1-9]+\d*',
                ],
                '/^\/foo\/([1-9]+\d*)\/bar$/',
                [
                    'exampleId' => '[1-9]+\d*',
                ],
            ],
            [
                '/foo/:exampleId/bar/:anotherId',
                [
                    'anotherId' => '[a-z]+',
                    'exampleId' => '[1-9]+\d*',
                ],
                '/^\/foo\/([1-9]+\d*)\/bar\/([a-z]+)$/',
                [
                    'exampleId' => '[1-9]+\d*',
                    'anotherId' => '[a-z]+',
                ],
            ],
        ];
    }
}
