<?php

namespace GraphReflection;

use SimpleXMLElement;

class ExSimpleXMLElement extends SimpleXMLElement
{  
    /**
     * Add SimpleXMLElement code into a SimpleXMLElement
     * @param SimpleXMLElement $append
     */
    public function appendXML($append)
    {
        $xml = null;
        if ($append) {
            if (strlen(trim((string)$append)) == 0) {
                $xml = $this->addChild($append->getName());
                foreach($append->children() as $child) {
                    $xml->appendXML($child);
                }
            } else {
                $xml = $this->addChild($append->getName(), (string)$append);
            }
            foreach($append->attributes() as $n => $v) {
                $xml->addAttribute($n, $v);
            }
        }
        return $xml;
    }
}
