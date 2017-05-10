<?php

declare(strict_types=1);

namespace Holokron\JsonPatch;

use Holokron\JsonPatch\Exception\NotMatchedException;
use Holokron\JsonPatch\Executor\ExecutorInterface;
use Holokron\JsonPatch\Matcher\MatcherInterface;
use Holokron\JsonPatch\Parser\ParserInterface;

/**
 * @author Michał Tęczyński <michalv8@gmail.com>
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
     * @var Validator
     */
    private $validator;

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

        $operationsToExecute = [];

        foreach ($document as $patch) {
            //$this->validator->validate($patch);
            $patch = Patch::create($patch);
            try {
                $matched = $this->matcher->match($patch);
            } catch (NotMatchedException $e) {
                if ($ignoreNotMatched) {
                    continue;
                }

                throw $e;
            }
            $operationsToExecute[] = [
                $matched,
                $patch->getValue(),
            ];
        }

        foreach ($operationsToExecute as $operation) {
            $this->executor->execute($operation[0][0], $operation[0][1], $subject, $operation[1]);
        }
    }
}
