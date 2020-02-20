<?php

namespace GraphReflection;

use SimpleXMLElement;

class ReflectionToGraphML implements ReflectionPrinter
{
    protected $indexNode = 0;
    protected $indexEdge = 0;    
    protected $ClassesList = array();
    protected $template = '
        <graph id="main" edgedefault="directed">
        </graph>
    ';
    protected $xml;

    public function __construct()
    {
        $this->xml = new SimpleXMLElement($this->template);
    }   

    public function print()
    {
        //header('Content-type: text/xml');
        //echo $this->xml->asXML();
        file_put_contents('test.graphml', $this->xml->asXML());
    }

    /**
     * @param string $nameNode
     * @return ReflectionNode
     */
    public function addNodeClass(string $nameNode) : ReflectionNode
    {
        if(isset($this->ClassesList[$nameNode])) 
            return $this->ClassesList[$nameNode];
        $node = $this->xml->addChild('node');
        $this->indexNode++;
        $node->addAttribute('id', 'n'.$this->indexNode);
        $dataNode = $node->addChild('data', $nameNode);
        $dataNode->addAttribute('key', 'd0');
        $dataNode = $node->addChild('data', 'blue');
        $dataNode->addAttribute('key', 'd1');
        $dataNode = $node->addChild('data', 'Class');
        $dataNode->addAttribute('key', 'd2');
        $this->ClassesList[$nameNode] = new GraphMLNode($node);
        return $this->ClassesList[$nameNode];
    }

    /**
     * @param string $nameInterface
     * @return ReflectionNode
     */
    public function addNodeInterface(string $nameInterface) : ReflectionNode
    {
        if(isset($this->ClassesList[$nameInterface])) 
            return $this->ClassesList[$nameInterface];
        $node = $this->xml->addChild('node');
        $this->indexNode++;
        $node->addAttribute('id', 'n'.$this->indexNode);
        $dataNode = $node->addChild('data', $nameInterface);
        $dataNode->addAttribute('key', 'd0');
        $dataNode = $node->addChild('data', 'green');
        $dataNode->addAttribute('key', 'd1');
        $dataNode = $node->addChild('data', 'Interface');
        $dataNode->addAttribute('key', 'd2');
        $this->ClassesList[$nameInterface] = new GraphMLNode($node);
        return $this->ClassesList[$nameInterface];
    }

    /**
     * @param string $namespaceName
     * @return ReflectionNode
     */
    /*public function addNodeNamespace(string $namespaceName) : ReflectionNode
    {

    }*/

    /**
     * @param string $nameTrait
     * @return ReflectionNode
     */
    public function addNodeTrait(string $nameTrait) : ReflectionNode
    {
        if(isset($this->ClassesList[$nameTrait])) 
            return $this->ClassesList[$nameTrait];
        $node = $this->xml->addChild('node');
        $this->indexNode++;
        $node->addAttribute('id', 'n'.$this->indexNode);
        $dataNode = $node->addChild('data', $nameTrait);
        $dataNode->addAttribute('key', 'd0');
        $dataNode = $node->addChild('data', 'red');
        $dataNode->addAttribute('key', 'd1');
        $dataNode = $node->addChild('data', 'Trait');
        $dataNode->addAttribute('key', 'd2');
        $this->ClassesList[$nameTrait] = new GraphMLNode($node);
        return $this->ClassesList[$nameTrait];
    }

    /**
     * @param ReflectionNode $parentNode
     * @param ReflectionNode $childNode
     */
    public function addEdge(ReflectionNode $parentNode, ReflectionNode $childNode)
    {
        $this->indexEdge++;
        $edge = $this->xml->addChild('edge');
        $edge->addAttribute('id', 'e'.$this->indexEdge);
        $edge->addAttribute('source', $parentNode->getId());
        $edge->addAttribute('target', $childNode->getId());
        $dataEdge = $edge->addChild('data', 'parent');
        $dataEdge->addAttribute('key', 'd3');
    }

}
