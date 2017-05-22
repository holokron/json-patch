<?php

declare(strict_types=1);

namespace Holokron\JsonPatch;

use Holokron\JsonPatch\Exception\NotMatchedException;
use Holokron\JsonPatch\Executor\ExecutorInterface;
use Holokron\JsonPatch\Matcher\MatcherInterface;
use Holokron\JsonPatch\Parser\ParserInterface;

/**
 * @author Michał Tęczyński <michal.teczynski@gmail.com>
 */
class Patcher
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

    public function __construct(
        ParserInterface $parser,
        MatcherInterface $matcher,
        ExecutorInterface $executor
    ) {
        $this->parser = $parser;
        $this->matcher = $matcher;
        $this->executor = $executor;
    }

    /**
     * @param string|array $json JSON string with patchs to apply
     */
    public function apply($json, $subject = null, bool $ignoreNotMatched = false)
    {
        if (is_array($json)) {
            $document = $json;
        }

        if (is_string($json)) {
            $document = $this->parser->parse($json);
        }

        if (empty($document)) {
            return;
        }

        $toExecute = [];

        foreach ($document as $jsonPatch) {
            $patch = Patch::create($jsonPatch);
            try {
                $toExecute[] = [
                    $this->matcher->match($patch),
                    $patch->getValue(),
                ];
            } catch (NotMatchedException $e) {
                if ($ignoreNotMatched) {
                    continue;
                }

                throw $e;
            }
        }

        foreach ($toExecute as $args) {
            $this->executor->execute($args[0][0], $args[0][1], $subject, $args[1]);
        }
    }
}
