<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Tests;

use Holokron\JsonPatch\Patch;
use Holokron\JsonPatch\Tests\Traits\MockTrait;
use PHPUnit\Framework\TestCase;

/**
 * @author Michał Tęczyński <michal.teczynski@gmail.com>
 */
class PatchTest extends TestCase
{
    use MockTrait;

    /**
     * @dataProvider dataCreate
     */
    public function testCreate(array $data, string $op, string $path, $value = null, $from = null)
    {
        $patch = Patch::create($data);
        $this->assertSame($op, $patch->getOp());
        $this->assertSame($path, $patch->getPath());
        $this->assertSame($value, $patch->getValue());
        $this->assertSame($from, $patch->getFrom());
    }

    public static function dataCreate(): array
    {
        return [
            [
                [
                    'op' => 'add',
                    'path' => '/foo/bar',
                    'value' => [
                        'foo' => 'bar',
                    ],
                ],
                'add',
                '/foo/bar',
                [
                    'foo' => 'bar',
                ],
            ],
            [
                [
                    'op' => 'remove',
                    'path' => '/foo/bar/123',
                ],
                'remove',
                '/foo/bar/123',
            ],
            [
                [
                    'op' => 'replace',
                    'path' => '/foo/bar/123',
                    'value' => [
                        'foo' => 'bar',
                    ],
                ],
                'replace',
                '/foo/bar/123',
                [
                    'foo' => 'bar',
                ],
            ],
            [
                [
                    'op' => 'copy',
                    'path' => '/foo/bar/123',
                    'from' => '/example/path',
                ],
                'copy',
                '/foo/bar/123',
                null,
                '/example/path',
            ],
            [
                [
                    'op' => 'move',
                    'path' => '/foo/bar/123',
                    'from' => '/example/path',
                ],
                'move',
                '/foo/bar/123',
                null,
                '/example/path',
            ],
            [
                [
                    'op' => 'test',
                    'path' => '/foo/bar/123',
                    'value' => [
                        'example' => 'value',
                    ],
                ],
                'test',
                '/foo/bar/123',
                [
                    'example' => 'value',
                ],
            ],
        ];
    }

    /**
     * @dataProvider      dataCreateWhenInvalidPatchGiven
     * @expectedException \Holokron\JsonPatch\Exception\InvalidPatchException
     */
    public function testCreateWhenInvalidPatchGiven(array $data)
    {
        $patch = Patch::create($data);
        var_dump($patch);
    }

    public static function dataCreateWhenInvalidPatchGiven(): array
    {
        return [
            [
                [
                    'op' => 'addd',
                    'path' => '/foo/bar',
                    'value' => [
                        'foo' => 'bar',
                    ],
                ],
            ],
            [
                [
                    'op' => 'remove',
                ],
            ],
            [
                [
                    'op' => 'replace',
                    'path' => '/foo/bar/123',
                ],
            ],
            [
                [
                    'op' => 'copy',
                    'path' => '/foo/bar/123',
                ],
            ],
            [
                [
                    'op' => 'move',
                    'path' => '/foo/bar/123',
                    'value' => [
                        'foo' => 'bar',
                    ],
                ],
            ],
            [
                [
                    'path' => '/foo/bar/123',
                    'value' => [
                        'example' => 'value',
                    ],
                ],
            ],
        ];
    }
}
