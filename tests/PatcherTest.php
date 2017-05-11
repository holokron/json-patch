<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Tests;

use Holokron\JsonPatch\Executor\ExecutorInterface;
use Holokron\JsonPatch\Matcher\MatcherInterface;
use Holokron\JsonPatch\Parser\ParserInterface;
use Holokron\JsonPatch\Patch;
use Holokron\JsonPatch\Patcher;
use Holokron\JsonPatch\Tests\Traits\MockTrait;
use PHPUnit\Framework\TestCase;

/**
 * @author Michał Tęczyński <michalv8@gmail.com>
 */
class PatcherTest extends TestCase
{
    use MockTrait;

    /**
     * @var Patcher
     */
    private $patcher;
    private $parser;
    private $matcher;
    private $executor;

    public function setUp()
    {
        $this->parser = $this->getMockForClass(ParserInterface::class);
        $this->matcher = $this->getMockForClass(MatcherInterface::class);
        $this->executor = $this->getMockForClass(ExecutorInterface::class);
        $this->patcher = new Patcher(
            $this->parser,
            $this->matcher,
            $this->executor
        );
    }

    /**
     * @dataProvider dataApplyWhenEmptyDocument
     */
    public function testApplyWhenEmptyDocument($json, int $parserInvokeCount)
    {
        $this->mockMethod($this->parser, 'parse', $this->exactly($parserInvokeCount), [$json], []);
        $this->mockMethod($this->matcher, 'match', $this->never());
        $this->mockMethod($this->executor, 'execute', $this->never());
        $this->patcher->apply($json);
    }

    public static function dataApplyWhenEmptyDocument(): array
    {
        return [
            ['[]', 1],
            ['{}', 1],
            [[], 0],
        ];
    }

    /**
     * @dataProvider dataApply
     */
    public function testApply(string $json, $subject, array $document, array $patches, array $matches, array $executionArgs = [])
    {
        $this
            ->mockMethod($this->parser, 'parse', $this->once(), [$json], $document)
            ->mockMethodConsecutive(
                $this->matcher,
                'match',
                $this->exactly(count($patches)),
                $patches,
                $matches
            )
            ->mockMethodConsecutive(
                $this->executor,
                'execute',
                $this->exactly(count($executionArgs)),
                $executionArgs
            );

        $this->patcher->apply($json, $subject);
    }

    public static function dataApply(): array
    {
        $exampleHandler = new class() {
            public function addItem()
            {
            }

            public function removeItem()
            {
            }
        };
        $exampleSubject = new \stdClass();
        $jsons = [
            '[{"op":"add","path":"/items","value":{"foo":"bar"},{"op":"reomve","path":"/items/123"]',
        ];
        $subjects = [
            $exampleSubject,
        ];
        $documents = [
            [
                [
                    'op' => 'add',
                    'path' => '/items',
                    'value' => [
                        'foo' => 'bar',
                    ],
                ],
                [
                    'op' => 'remove',
                    'path' => '/items/123',
                ],
            ],
        ];
        $matches = [
            [
                [
                    [$exampleHandler, 'addItem'],
                    [],
                ],
                [
                    [$exampleHandler, 'removeItem'],
                    [123],
                ],
            ],
        ];

        $items = [];

        foreach ($jsons as $key => $json) {
            $items[] = [
                $json,
                $subjects[$key],
                $documents[$key],
                array_map(
                    function (array $doc) {
                        return Patch::create($doc);
                    },
                    $documents[$key]
                ),
                $matches[$key],
                array_map(
                    function (array $match, array $doc) use ($key, $subjects): array {
                        return [
                            $match[0],
                            $match[1],
                            $subjects[$key],
                            $doc['value'] ?? null,
                        ];
                    },
                    $matches[$key],
                    $documents[$key]
                ),
            ];
        }

        return $items;
    }
}
