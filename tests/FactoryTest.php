<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Tests;

use Holokron\JsonPatch\Executor\ExecutorInterface;
use Holokron\JsonPatch\Factory;
use Holokron\JsonPatch\Matcher\MatcherInterface;
use Holokron\JsonPatch\Parser\ParserInterface;
use Holokron\JsonPatch\Tests\Traits\MockTrait;
use PHPUnit\Framework\TestCase;

/*
 * @author Michał Tęczyński <michal.teczynski@gmail.com>
 */
class FactoryTest extends TestCase
{
    use MockTrait;

    public function testCreate()
    {
        $expectedParser = $this->getMockForClass(ParserInterface::class);
        $expectedMatcher = $this->getMockForClass(MatcherInterface::class);
        $expectedExecutor = $this->getMockForClass(ExecutorInterface::class);

        $factory = new Factory();
        $patcher = $factory
            ->setParser($expectedParser)
            ->setMatcher($expectedMatcher)
            ->setExecutor($expectedExecutor)
            ->create()
            ;

        $reflection = new \ReflectionClass($patcher);
        $parser = $reflection->getProperty('parser');
        $matcher = $reflection->getProperty('matcher');
        $executor = $reflection->getProperty('executor');
        $parser->setAccessible(true);
        $matcher->setAccessible(true);
        $executor->setAccessible(true);

        $this->assertSame($expectedParser, $parser->getValue($patcher));
        $this->assertSame($expectedMatcher, $matcher->getValue($patcher));
        $this->assertSame($expectedExecutor, $executor->getValue($patcher));
    }
}