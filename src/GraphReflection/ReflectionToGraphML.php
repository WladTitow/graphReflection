<?php

namespace GraphReflection;

use SimpleXMLElement;

class ReflectionToGraphML implements ReflectionPrinter
{
    protected $indexNode = 0;
    protected $indexEdge = 0;    
    protected $ClassesList = array();
    protected $xml;

    public function __construct()
    {
        $this->xml = new ExSimpleXMLElement('
            <graphml xsi:schemaLocation="http://graphml.graphdrawing.org/xmlns http://www.yworks.com/xml/schema/graphml.html/2.0/ygraphml.xsd " 
            xmlns="http://graphml.graphdrawing.org/xmlns" 
            xmlns:demostyle="http://www.yworks.com/yFilesHTML/demos/FlatDemoStyle/1.0" 
            xmlns:icon-style="http://www.yworks.com/yed-live/icon-style/1.0" 
            xmlns:bpmn="http://www.yworks.com/xml/yfiles-bpmn/2.0" 
            xmlns:demotablestyle="http://www.yworks.com/yFilesHTML/demos/FlatDemoTableStyle/1.0" 
            xmlns:uml="http://www.yworks.com/yFilesHTML/demos/UMLDemoStyle/1.0" 
            xmlns:compat="http://www.yworks.com/xml/yfiles-compat-arrows/1.0" 
            xmlns:GraphvizNodeStyle="http://www.yworks.com/yFilesHTML/graphviz-node-style/1.0" 
            xmlns:VuejsNodeStyle="http://www.yworks.com/demos/yfiles-vuejs-node-style/1.0" 
            xmlns:y="http://www.yworks.com/xml/yfiles-common/3.0" 
            xmlns:x="http://www.yworks.com/xml/yfiles-common/markup/3.0" 
            xmlns:yjs="http://www.yworks.com/xml/yfiles-for-html/2.0/xaml" 
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
            <key id="d0" for="node" attr.type="boolean" attr.name="Expanded" y:attr.uri="http://www.yworks.com/xml/yfiles-common/2.0/folding/Expanded">
                <default>true</default>
            </key>
            <key id="d1" for="node" attr.type="string" attr.name="url"/>
            <key id="d2" for="node" attr.type="string" attr.name="description"/>
            <key id="d3" for="node" attr.name="NodeLabels" y:attr.uri="http://www.yworks.com/xml/yfiles-common/2.0/NodeLabels"/>
            <key id="d4" for="node" attr.name="NodeGeometry" y:attr.uri="http://www.yworks.com/xml/yfiles-common/2.0/NodeGeometry"/>
            <key id="d5" for="all" attr.name="UserTags" y:attr.uri="http://www.yworks.com/xml/yfiles-common/2.0/UserTags"/>
            <key id="d6" for="node" attr.name="NodeStyle" y:attr.uri="http://www.yworks.com/xml/yfiles-common/2.0/NodeStyle"/>
            <key id="d7" for="node" attr.name="NodeViewState" y:attr.uri="http://www.yworks.com/xml/yfiles-common/2.0/folding/1.1/NodeViewState"/>
            <key id="d8" for="edge" attr.type="string" attr.name="url"/>
            <key id="d9" for="edge" attr.type="string" attr.name="description"/>
            <key id="d10" for="edge" attr.name="EdgeLabels" y:attr.uri="http://www.yworks.com/xml/yfiles-common/2.0/EdgeLabels"/>
            <key id="d11" for="edge" attr.name="EdgeGeometry" y:attr.uri="http://www.yworks.com/xml/yfiles-common/2.0/EdgeGeometry"/>
            <key id="d12" for="edge" attr.name="EdgeStyle" y:attr.uri="http://www.yworks.com/xml/yfiles-common/2.0/EdgeStyle"/>
            <key id="d13" for="edge" attr.name="EdgeViewState" y:attr.uri="http://www.yworks.com/xml/yfiles-common/2.0/folding/1.1/EdgeViewState"/>
            <key id="d14" for="port" attr.name="PortLabels" y:attr.uri="http://www.yworks.com/xml/yfiles-common/2.0/PortLabels"/>
            <key id="d15" for="port" attr.name="PortLocationParameter" y:attr.uri="http://www.yworks.com/xml/yfiles-common/2.0/PortLocationParameter">
                <default>
                    <x:Static Member="y:FreeNodePortLocationModel.NodeCenterAnchored"/>
                </default>
            </key>
            <key id="d16" for="port" attr.name="PortStyle" y:attr.uri="http://www.yworks.com/xml/yfiles-common/2.0/PortStyle">
                <default>
                    <x:Static Member="y:VoidPortStyle.Instance"/>
                </default>
            </key>
            <key id="d17" for="port" attr.name="PortViewState" y:attr.uri="http://www.yworks.com/xml/yfiles-common/2.0/folding/1.1/PortViewState"/>
            <key id="d18" attr.name="SharedData" y:attr.uri="http://www.yworks.com/xml/yfiles-common/2.0/SharedData"/>
            <data key="d18">
                <y:SharedData>
                    <y:ExteriorLabelModel x:Key="1" Insets="5"/>
                </y:SharedData>
            </data>
            </graphml>
        ');
    }   

    public function print()
    {
        header('Content-type: text/xml');
        $graph = $this->xml->asXML();
        $template = array('xList', 'yLabel', 'yjsShapeNodeStyle', 'yjsPolylineEdgeStyle', 'yjsStroke', 
        'yCompositeLabelModel', 'yExteriorLabelModelParameter', 'yjsDefaultLabelStyle',
        'yGraphMLReference', 'yInteriorLabelModel', 'yFreeNodeLabelModel', 'yjsFont');
        $replace = array('x:List', 'y:Label', 'yjs:ShapeNodeStyle', 'yjs:PolylineEdgeStyle', 'yjs:Stroke', 
        'y:CompositeLabelModel', 'y:ExteriorLabelModelParameter', 'yjs:DefaultLabelStyle',
        'y:GraphMLReference', 'y:InteriorLabelModel', 'y:FreeNodeLabelModel', 'yjs:Font');
        $graph = str_replace($template, $replace, $graph);
        echo $graph;
        //file_put_contents('test.graphml', $graph);
    }

    /**
     * @param string $nameNode
     * @return ReflectionNode
     */
    public function addNodeClass(string $nameNode) : ReflectionNode
    {
        if(isset($this->ClassesList[$nameNode])) 
            return $this->ClassesList[$nameNode];
        $this->indexNode++;
        $newNode = new SimpleXMLElement(
        '<node id="n'.$this->indexNode.'" type="class">
			<data key="d2"><![CDATA['.$this->stripInvalidXml($nameNode).']]></data>
			<data key="d3">
				<xList>
					<yLabel>
						<yLabel.Text><![CDATA['.$this->stripInvalidXml($nameNode).']]></yLabel.Text>
						<yLabel.LayoutParameter>
							<yCompositeLabelModelParameter>
								<yCompositeLabelModelParameter.Parameter>
									<yExteriorLabelModelParameter Position="South" Model="{y:GraphMLReference 1}"/>
								</yCompositeLabelModelParameter.Parameter>
								<yCompositeLabelModelParameter.Model>
									<yCompositeLabelModel>
										<yCompositeLabelModel.LabelModels>
											<yGraphMLReference ResourceKey="1"/>
											<yInteriorLabelModel/>
											<yFreeNodeLabelModel/>
										</yCompositeLabelModel.LabelModels>
									</yCompositeLabelModel>
								</yCompositeLabelModelParameter.Model>
							</yCompositeLabelModelParameter>
						</yLabel.LayoutParameter>
						<yLabel.Style>
							<yjsDefaultLabelStyle verticalTextAlignment="BOTTOM" horizontalTextAlignment="CENTER" textFill="BLACK">
								<yjsDefaultLabelStyle.font>
									<yjsFont fontSize="12" fontFamily="\'Arial\'"/>
								</yjsDefaultLabelStyle.font>
							</yjsDefaultLabelStyle>
						</yLabel.Style>
					</yLabel>
				</xList>
			</data>
        </node>');        
        $this->xml->appendXML($newNode);
        $this->ClassesList[$nameNode] = new GraphMLNode($newNode);
        return $this->ClassesList[$nameNode];
    }

    public function stripInvalidXml($value)
    {
        $ret = "";
        $current;
        if (empty($value)) 
        {
            return $ret;
        }

        $length = strlen($value);
        for ($i=0; $i < $length; $i++)
        {
            $current = ord($value{$i});
            if (($current == 0x9) ||
                ($current == 0xA) ||
                ($current == 0xD) ||
                (($current >= 0x20) && ($current <= 0xD7FF)) ||
                (($current >= 0xE000) && ($current <= 0xFFFD)) ||
                (($current >= 0x10000) && ($current <= 0x10FFFF)))
            {
                $ret .= chr($current);
            }
            else
            {
                $ret .= " ";
            }
        }
        return $ret;
    }

    /**
     * @param string $nameInterface
     * @return ReflectionNode
     */
    public function addNodeInterface(string $nameInterface) : ReflectionNode
    {
        if(isset($this->ClassesList[$nameInterface])) 
            return $this->ClassesList[$nameInterface];
        $this->indexNode++;
        $newNode = new SimpleXMLElement(
        '<node id="n'.$this->indexNode.'" type="interface">
			<data key="d2">'.$nameInterface.'</data>
			<data key="d3">
				<xList>
					<yLabel LayoutParameter="{y:GraphMLReference 3}" Style="{y:GraphMLReference 4}">
						<yLabel.Text>'.$nameInterface.'</yLabel.Text>
					</yLabel>
				</xList>
            </data>
            <data key="d6">
                <yjsShapeNodeStyle stroke="LIME" fill="LIME"/>
            </data>
        </node>');        
        $this->xml->appendXML($newNode);
        $this->ClassesList[$nameInterface] = new GraphMLNode($newNode);
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
        $this->indexNode++;
        $newNode = new SimpleXMLElement(
        '<node id="n'.$this->indexNode.'" type="trait">
			<data key="d2">'.$nameTrait.'</data>
			<data key="d3">
				<xList>
					<yLabel LayoutParameter="{y:GraphMLReference 3}" Style="{y:GraphMLReference 4}">
						<yLabel.Text>'.$nameTrait.'</yLabel.Text>
					</yLabel>
				</xList>
            </data>
            <data key="d6">
                <yjsShapeNodeStyle stroke="#AAAA0099" fill="#AAAA0099"/>
            </data>
        </node>');        
        $this->xml->appendXML($newNode);
        $this->ClassesList[$nameTrait] = new GraphMLNode($newNode);
        return $this->ClassesList[$nameTrait];
    }

    /**
     * @param ReflectionNode $parentNode
     * @param ReflectionNode $childNode
     */
    public function addEdge(ReflectionNode $parentNode, ReflectionNode $childNode)
    {
        $this->indexEdge++;        
        $template =
        '<edge id="e'.$this->indexEdge.'" source="'.$parentNode->getId().'" target="'.$childNode->getId().'">
			<data key="d10">
				<xList>
					<yLabel Style="{y:GraphMLReference 4}">
						<yLabel.Text>parent</yLabel.Text>						
					</yLabel>
				</xList>
			</data>
		';        
        if($parentNode->getType() == 'interface')
        $template .= '
        <data key="d12">
			<yjsPolylineEdgeStyle targetArrow="{y:GraphMLReference 9}">
				<yjsPolylineEdgeStyle.stroke>
					<yjsStroke fill="{y:GraphMLReference 7}" lineCap="SQUARE" dashStyle="Dot"/>
				</yjsPolylineEdgeStyle.stroke>
			</yjsPolylineEdgeStyle>
        </data>';
        $template .= '</edge>';
        $newEdge = new SimpleXMLElement($template);
        $this->xml->appendXML($newEdge);
    }

}
