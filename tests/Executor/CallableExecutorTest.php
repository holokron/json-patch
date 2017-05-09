<?php

declare(strict_types=1);

namespace Holokron\Tests\JsonPatch\Executor;

use Holokron\JsonPatch\Executor\CallableExecutor;
use PHPUnit\Framework\TestCase;

class CallableExecutorTest extends TestCase
{
    /**
     * @var CallableExecutor
     */
    private $executor;

    public function setUp()
    {
        $this->executor = new CallableExecutor();
    }

    public function testExecuteWithoutSubject()
    {
        $foo = 'foo';
        $bar = 123;
        $that = $this;
        $callback = new class($this, $foo, $bar) {
            public function __construct(TestCase $testCase, string $foo, int $bar)
            {
                $this->testCase = $testCase;
                $this->foo = $foo;
                $this->bar = $bar;
            }

            public function test(...$args)
            {
                $this->testCase->assertSame($this->foo, $args[0]);
                $this->testCase->assertSame($this->bar, $args[1]);

                return 'executed';
            }
        };
        $result = $this->executor->execute([$callback, 'test'], [$foo, $bar]);
        $this->assertSame('executed', $result);
    }

    public function testExecuteWithSubject()
    {
        $foo = 'foo';
        $bar = 123;
        $subject = new \stdClass();
        $that = $this;
        $callback = new class($this, $foo, $bar, $subject) {
            public function __construct(TestCase $testCase, string $foo, int $bar, $subject)
            {
                $this->testCase = $testCase;
                $this->foo = $foo;
                $this->bar = $bar;
                $this->subject = $subject;
            }

            public function test(...$args)
            {
                $this->testCase->assertSame($this->subject, $args[0]);
                $this->testCase->assertSame($this->foo, $args[1]);
                $this->testCase->assertSame($this->bar, $args[2]);

                return 'executed';
            }
        };
        $result = $this->executor->execute([$callback, 'test'], [$foo, $bar], $subject);
        $this->assertSame('executed', $result);
    }
}
