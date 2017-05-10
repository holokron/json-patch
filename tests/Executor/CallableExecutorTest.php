<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Tests\Executor;

use Holokron\JsonPatch\Executor\CallableExecutor;
use PHPUnit\Framework\TestCase;

/**
 * @author Michał Tęczyński <michalv8@gmail.com>
 */
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

    public function testExecute()
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

            public function executed(...$args)
            {
                $this->testCase->assertSame($this->foo, $args[0], 'Invalid method argument at position 0');
                $this->testCase->assertSame($this->bar, $args[1], 'Invalid method argument at position 1');

                return 'executed';
            }
        };
        $result = $this->executor->execute([$callback, 'executed'], [$foo, $bar]);
        $this->assertSame('executed', $result, 'Invalid method executed');
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

            public function executeWithSubject(...$args)
            {
                $this->testCase->assertSame($this->subject, $args[0], 'Invalid subject argument at position 0');
                $this->testCase->assertSame($this->foo, $args[1], 'Invalid method argument at position 1');
                $this->testCase->assertSame($this->bar, $args[2], 'Invalid method argument at position 2');

                return 'executeWithSubject';
            }
        };
        $result = $this->executor->execute([$callback, 'executeWithSubject'], [$foo, $bar], $subject);
        $this->assertSame('executeWithSubject', $result, 'Invalid method executed');
    }

    public function testExecuteWithValue()
    {
        $foo = 'foo';
        $bar = 123;
        $value = new \stdClass();
        $that = $this;
        $callback = new class($this, $foo, $bar, $value) {
            public function __construct(TestCase $testCase, string $foo, int $bar, $value)
            {
                $this->testCase = $testCase;
                $this->foo = $foo;
                $this->bar = $bar;
                $this->value = $value;
            }

            public function executedExecuteWithValue(...$args)
            {
                $this->testCase->assertSame($this->foo, $args[0], 'Invalid method argument at position 0');
                $this->testCase->assertSame($this->bar, $args[1], 'Invalid method argument at position 1');
                $this->testCase->assertSame($this->value, $args[2], 'Invalid value argument at position 2');

                return 'executedExecuteWithValue';
            }
        };
        $result = $this->executor->execute([$callback, 'executedExecuteWithValue'], [$foo, $bar], null, $value);
        $this->assertSame('executedExecuteWithValue', $result, 'Invalid method executed');
    }
}
