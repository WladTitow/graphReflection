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
        $classMap = $classLoader->getPrefixesPsr4() + $classLoader->getPrefixes();       
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
            $node = $classData->getName();
            $printer->addNodeClass($node);  
            if($parent = $classData->getParentClass()) {
                $printer->addParentClass($node, $parent->getNamespaceName());            
            }
        }
        $printer->print();
    }
}
