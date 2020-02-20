<?php

namespace GraphReflection;

use SimpleXMLElement;

class GraphMLNode implements ReflectionNode
{

    protected $xml;

    /**
     * @param SimpleXMLElement $xmlNode
     */
    public function __construct(SimpleXMLElement &$xmlNode)
    {
        $this->xml = &$xmlNode;
    }   

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->xml->attributes()['id'];
    }

}
