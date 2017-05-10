<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Tests;

use Holokron\JsonPatch\Executor\ExecutorInterface;
use Holokron\JsonPatch\Matcher\MatcherInterface;
use Holokron\JsonPatch\Parser\ParserInterface;
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
}
