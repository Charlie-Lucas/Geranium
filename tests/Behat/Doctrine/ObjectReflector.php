<?php

namespace App\Tests\Behat\Doctrine;

class ObjectReflector
{
    public function getReflectionClass($object): \ReflectionClass
    {
        return new \ReflectionClass($object);
    }

    public function getClassName($object): string
    {
        return $this->getReflectionClass($object)->getShortName();
    }

    public function getClassNamespace($object): string
    {
        return $this->getReflectionClass($object)->getNamespaceName();
    }

    public function getClassLongName($object): string
    {
        return sprintf(
            "%s\\%s",
            $this->getClassNamespace($object),
            $this->getClassName($object)
        );
    }

    public function isInstanceOf($object, $class): bool
    {
        return $object instanceof $class || $this->getClassLongName($object) === $class;
    }

    public function getReflectionsFromMetadata($metadata): array
    {
        return array_map(
            function ($e) {
                return $this->getReflectionClass($e->name);
            },
            $metadata
        );
    }
}
