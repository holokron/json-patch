<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Tests\Matcher;

use Holokron\JsonPatch\Definition\DefinitionsCollection;
use Holokron\JsonPatch\Matcher\Matcher;
use Holokron\JsonPatch\Patch;
use Holokron\JsonPatch\Tests\Traits\MockTrait;
use PHPUnit\Framework\TestCase;

/*
 * @author Michał Tęczyński <michal.teczynski@gmail.com>
 */
class MatcherTest extends TestCase
{
    use MockTrait;

    /**
     * @var Matcher
     */
    private $matcher;

    protected function setUp()
    {
        $this->matcher = new Matcher();
    }

    /**
     * @expectedException \Holokron\JsonPatch\Exception\NotMatchedException
     */
    public function testMatchWhenNoDefinitionsIsMatched()
    {
        $definitions = new DefinitionsCollection();
        $this->matcher->setDefinitions($definitions);
        $patch = $this->getMockForClass(Patch::class);
        $this->mockMethod($patch, 'getOp', $this->exactly($definitions->count()), null, 'add');

        $this->matcher->match($patch);
    }
}
