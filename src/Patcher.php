<?php

declare(strict_types=1);

namespace Holokron\JsonPatch;

use Holokron\JsonPatch\Exception\NotMatchedException;
use Holokron\JsonPatch\Executor\ExecutorInterface;

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

        foreach ($patch as $patchOperation) {
            $this->validator->validate($patch);
            $patch = Patch::create($patch);
            try {
                $matched = $this->matcher->match($patch);
            } catch (NotMatchedException $e) {
                if ($ignoreNotMatched) {
                    continue;
                }

                throw $e;
            }
            $operationsToExecute[] = $matched;
        }

        foreach ($operationsToExecute as $operation) {
            $this->executor->execute($operation[0], $operation[1], $subject);
        }
    }
}
