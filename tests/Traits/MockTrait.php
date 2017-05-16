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

        $expects = $mock
            ->expects($invoke)
            ->method($method);

        if (is_callable($return) && null === $args) {
            $expects->will($this->returnCallback($return));
        } elseif (is_array($args)) {
            $expects->with(...$args);
            if ($return instanceof \Throwable) {
                $expects->will($this->throwException($return));
            } else {
                $expects->willReturn($return);
            }
        }

        return $this;
    }

    private function mockMethodConsecutive($mock, string $method, $invoke = null, array $args = [], array $return = [])
    {
        if (!$invoke instanceof \PHPUnit_Framework_MockObject_Matcher_InvokedCount) {
            $invoke = $this->any();
        }

        $return = array_map(
            function ($item) {
                if (!$item instanceof \Throwable) {
                    return $item;
                }

                return $this->throwException($item);
            },
            $return
        );

        $mock
            ->expects($invoke)
            ->method($method)
            ->withConsecutive(...$args)
            ->will($this->onConsecutiveCalls(...$return));

        return $this;
    }
}
