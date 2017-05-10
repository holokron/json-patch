<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Tests\Traits;

trait MockTrait
{
    private function getMockForClass(string $className)
    {
        return $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function mockMethod($mock, string $method, $invoke = null, $args = null, $return = null): self
    {
        if (!$invoke instanceof \PHPUnit_Framework_MockObject_Matcher_InvokedCount) {
            $invoke = $this->any();
        }

        if (!is_array($args)) {
            $args = [$args];
        }

        $mock
            ->expects($invoke)
            ->method($method)
            ->with(...$args)
            ->willReturn($return);

        return $this;
    }
}
