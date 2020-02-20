<?php

namespace GraphReflection;

interface ReflectionPrinter 
{
    /**
     * @param string $nameNode
     * @return ReflectionNode
     */
    public function addNodeClass(string $nameNode) : ReflectionNode;

    /**
     * @param string $nameInterface
     * @return ReflectionNode
     */
    public function addNodeInterface(string $nameInterface) : ReflectionNode;

    /**
     * @param string $namespaceName
     * @return ReflectionNode
     */
    //public function addNodeNamespace(string $namespaceName) : ReflectionNode;

    /**
     * @param string $nameTrait
     * @return ReflectionNode
     */
    public function addNodeTrait(string $nameTrait) : ReflectionNode;

    /**
     * @param ReflectionNode $parentNode
     * @param ReflectionNode $childNode
     */
    public function addEdge(ReflectionNode $parentNode, ReflectionNode $childNode);
}
