<?php

namespace GraphReflection;

use SimpleXMLElement;

class ReflectionToGraphML implements ReflectionPrinter
{
    protected $indexNode = 0;
    protected $indexEdge = 0;
    protected $nameList = array();
    protected $template = '
        <graph id="main" edgedefault="directed">
        </graph>
    ';
    protected $xml;

    public function __construct()
    {
        $this->xml = new SimpleXMLElement($this->template);
    }   

    public function addNodeClass(string $name): int
    {
        if(isset($this->nameList[$name])) 
            return $this->nameList[$name];
        $this->indexNode++;
        $node = $this->xml->addChild('node');
        $node->addAttribute('id', $this->indexNode);
        $this->nameList[$name] = $this->indexNode;
        return $this->indexNode;
    }

    public function addParentClass(string $nameNode, string $nameParent)
    {
        $idNode = $this->addNodeClass($nameNode);
        $idParent = $this->addNodeClass($nameParent);
        $this->indexEdge++;
        $edge = $this->xml->addChild('edge');
        $edge->addAttribute('id', $this->indexEdge);
        $edge->addAttribute('source', $idNode);
        $edge->addAttribute('target', $idParent);
        return $this->indexEdge;
    }

    public function print()
    {
        header('Content-type: text/xml');
        echo $this->xml->asXML();
    }
}
