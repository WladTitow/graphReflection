<?php

namespace GraphReflection;

use ReflectionClass;
use Composer\Autoload\ClassLoader;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\SourceLocator\Type\ComposerSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;

class GraphReflection
{
    private $classes = array();
    /**
     * @param ClassLoader $classLoader
     */
    public function reflect(ClassLoader $classLoader)
    {        
        $classMap = $classLoader->getPrefixes();     //$classLoader->getPrefixesPsr4()  
        foreach ($classMap as $rootName => $directories) {
            foreach ($directories as $key => $directory) {
                $astLocator = (new BetterReflection())->astLocator();
                $directoriesSourceLocator = new DirectoriesSourceLocator([$directory], $astLocator);
                $reflector = new ClassReflector($directoriesSourceLocator);
                $this->classes += $reflector->getAllClasses();
            }
        }
    }

    /**
     * @param ReflectionPrinter $printer
     */
    public function print(ReflectionPrinter $printer)
    {
        foreach ($this->classes as $classesKey => $classData) { 
            if($classData->isInterface())  
                $node = $printer->addNodeInterface($classData->getName());
            else
                $node = $printer->addNodeClass($classData->getName());
            //$namespaceName = $classData->getNamespaceName();
            //$namespace = $printer->addNodeNamespace($namespaceName);
            //$printer->addEdge($namespace, $node);
            $interfaceNames = $classData->getInterfaceNames();
            foreach ($interfaceNames as $key => $name) {
                $interfaceNode = $printer->addNodeInterface($name);
                $printer->addEdge($interfaceNode, $node);
            }
            $traitNames = $classData->getTraitNames();
            foreach ($traitNames as $key => $name) {
                $traitNode = $printer->addNodeTrait($name);
                $printer->addEdge($traitNode, $node);
            }
            /*if($classData->getName() == 'GraphReflection\ExSimpleXMLElement') {
                echo '*';
                print_r($classData->getParentClass());
                echo '*';
                exit;
            }*/
            if($parent = $classData->getParentClass()) {
                $parentNode = $printer->addNodeClass($parent->getName());
                $printer->addEdge($parentNode, $node);            
            }
        }
        $printer->print();
    }
}
