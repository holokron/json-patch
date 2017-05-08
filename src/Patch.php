<?php

declare(strict_types=1);

class Patch
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
    private $operationValidator;

    /**
     * @var OperationFactory
     */
    private $operationFactory;

    /**
     * @param string $json JSON string with operations to apply
     */
    public function apply(string $json, $subject = null)  
    {
        $patch = $this->parser->parse($json);
        if (empty($patch)) {
            return;
        }

        foreach($patch as $patchOperation) {
            $this->validator->validate($operation);
            $operation = $this->operationFactory->create($patchOperation);
            $definition = $this->matcher->match($operation);
            $this->executeOperation($operation, $definition, $subject);
        }
    }

    private function executeOperation(DefinitionInterface $definition, OperationInterface $operation, $subject = null)
    {
        $args = $this->paramsConverter->convert($definition->compileParams($operation));
        
        if (null !== $subject) {
            array_unshift($args, $subject);
        }

        call_user_func_array($definition->getCallback(), $args);       
    }
}