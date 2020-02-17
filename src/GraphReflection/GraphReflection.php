<?php

namespace GraphReflection;

use Composer\Autoload\ClassLoader;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\SourceLocator\Type\ComposerSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;

class GraphReflection
{
    /**
     * @param ClassLoader $classLoader
     */
    public function reflect(ClassLoader $classLoader)
    {        
        $classMap = $classLoader->getPrefixesPsr4();       
        foreach ($classMap as $rootName => $directories) {
            foreach ($directories as $key => $directory) {
                $astLocator = (new BetterReflection())->astLocator();
                $directoriesSourceLocator = new DirectoriesSourceLocator([$directory], $astLocator);
                $reflector = new ClassReflector($directoriesSourceLocator);
                $classes = $reflector->getAllClasses();
                foreach ($classes as $classesKey => $classData) {                    
                    echo $classData->getFileName().'<br>';
                }
            }
        }

        /*
        $classMap = $classLoader->getPrefixesPsr4();

        $astLocator = (new BetterReflection())->astLocator();
        $reflector = new ClassReflector(new ComposerSourceLocator($classLoader, $astLocator));
        $reflectionClass = $reflector->reflect('Foo\Bar\MyClass');

        print_r(get_declared_classes());
        echo $reflectionClass->getShortName();
        echo $reflectionClass->getName(); 
        echo $reflectionClass->getNamespaceName(); 
        */
    }

    /**
     * @param ReflectionPrinter $printer
     */
    public function print(ReflectionPrinter $printer)
    {

    }
}
