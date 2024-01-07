<?php

declare(strict_types=1);

namespace Akawalko\RedbeanExtras\Finder;

use hanneskod\classtools\Iterator\ClassIterator;
use Symfony\Component\Finder\Finder;

class ModelClassFinder
{
    public static $baseModelClass = '\RedBeanPHP\SimpleModel';

    public function findInDirectory(string $directoryToSearchIn): array
    {
        $finder = new Finder();
        $iterator = new ClassIterator($finder->in($directoryToSearchIn));
        $iterator->enableAutoloading();
        $indexedClassNames = [];

        /** @var ReflectionClass $class */
        foreach ($iterator->type(self::$baseModelClass) as $class) {
            $indexedClassNames[$this->convertToSnakeCase($class->getShortName())] = $class->getName();
        }

        return $indexedClassNames;
    }

    protected function convertToSnakeCase(string $camel): string
    {
        return ltrim(
            strtolower(
                preg_replace('/(?<=[a-z])([A-Z])|([A-Z])(?=[a-z])/', '_$1$2', $camel)
            ),
            '_'
        );
    }
}
