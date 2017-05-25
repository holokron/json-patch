<?php

declare(strict_types=1);

namespace Holokron\JsonPatch;

use Holokron\JsonPatch\Executor\CallableExecutor;
use Holokron\JsonPatch\Executor\ExecutorInterface;
use Holokron\JsonPatch\Matcher\Matcher;
use Holokron\JsonPatch\Matcher\MatcherInterface;
use Holokron\JsonPatch\Parser\JsonDecodeParser;
use Holokron\JsonPatch\Parser\ParserInterface;

/**
 * @author Michał Tęczyński <michal.teczynski@gmail.com>
 */
class Factory
{
    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * @var MatcherInterface
     */
    private $matcher;

    /**
     * @var ExecutorInterface
     */
    private $executor;

    public function __construct()
    {
        $this->parser = new JsonDecodeParser();
        $this->matcher = new Matcher();
        $this->executor = new CallableExecutor();
    }

    public function setParser(ParserInterface $parser): self
    {
        $this->parser = $parser;

        return $this;
    }

    public function setMatcher(MatcherInterface $matcher): self
    {
        $this->matcher = $matcher;

        return $this;
    }

    public function setExecutor(ExecutorInterface $executor): self
    {
        $this->executor = $executor;

        return $this;
    }

    public function create(): Patcher
    {
        return new Patcher($this->parser, $this->matcher, $this->executor);
    }
}
