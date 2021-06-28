<?php

namespace App\Tests\Behat\Doctrine;

use App\Tests\Behat\Utils\TextFormatter;
use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Doctrine\Inflector\LanguageInflectorFactory;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;

class EntityResolver
{
    const CASE_CAMEL      = 'CamelCase';
    const CASE_UNDERSCORE = 'UnderscoreCase';

    protected ObjectReflector $reflector;
    protected TextFormatter $formatter;
    private Inflector $inflector;

    public function __construct(ObjectReflector $reflector, TextFormatter $formatter)
    {
        $this->reflector = $reflector;
        $this->formatter  = $formatter;
        $this->inflector = InflectorFactory::create()->build();
    }


    public function resolve(ObjectManager $entityManager, $name, $namespaces = '')
    {
        $results = [];

        $namespaces = is_array($namespaces) ? $namespaces : [ $namespaces ];

        foreach ($namespaces as $namespace) {
            $results = $this->getClassesFromName($entityManager, $name, $namespace, $results);
        }

        if (0 === count($results)) {

            return;
        }

        return $results;
    }

    protected function getClassesFromName(ObjectManager $entityManager, $name, $namespace, array $results = []): array
    {
        if (!empty($results)) {

            return $results;
        }

        $allMetadata = $entityManager->getMetadataFactory()->getAllMetadata();
        $allClass = $this->reflector->getReflectionsFromMetadata($allMetadata);
        foreach ($this->entityNameProposal($name) as $name) {
            $class = array_filter(
                $allClass,
                function ($e) use ($namespace, $name) {
                    $nameValid = strtolower($e->getShortName()) === strtolower($name);

                    return '' === $namespace
                        ? $nameValid
                        : $namespace === substr($e->getNamespaceName(), 0, strlen($namespace)) && $nameValid
                    ;
                }
            );
            $results = array_merge($results, $class);
        }

        return $results;
    }

    public function getMetadataFromProperty(ObjectManager $entityManager, $entity, $property)
    {
        $metadata = $this->getMetadataFromObject($entityManager, $entity);

        if (null !== $map = $this->getMappingFromMetadata($metadata, $property)) {
            return $map;
        }

        if ($this->asAccessForCase($entity, $property, self::CASE_CAMEL) || $this->asAccessForCase($entity, $property, self::CASE_UNDERSCORE)) {
            return false;
        }

        throw new \RuntimeException(
            sprintf(
                'Can\'t find property %s or %s in class %s',
                $this->formatter->toCamelCase(strtolower($property)),
                $this->formatter->toUnderscoreCase(strtolower($property)),
                get_class($entity)
            )
        );
    }

    public function getMetadataFromObject(ObjectManager $entityManager, $object): ClassMetadata
    {
        return $entityManager
            ->getMetadataFactory()
            ->getMetadataFor(get_class($object)
        );
    }

    public function entityNameProposal($name)
    {
        $name = strtolower(str_replace(" ", "", $name));

        $results = [$this->inflector->singularize($name), $this->inflector->pluralize($name), $name];

        return array_unique($results);
    }

    public function asAccessForCase($entity, $property, $case)
    {
        $method = sprintf('to%s', $case);

        return property_exists($entity, $this->formatter->{$method}($property)) || method_exists($entity, 'set' . $this->formatter->{$method}($property));
    }

    protected function getMappingFromMetadata(ClassMetadata $metadata, $property)
    {
        if (null !== $map = $this->getMappingFromMetadataPart($metadata->fieldMappings, $property)) {
            return $map;
        }

        if (null !== $map = $this->getMappingFromMetadataPart($metadata->associationMappings, $property)) {
            return $map;
        }
    }

    protected function getMappingFromMetadataPart($metadata, $property)
    {
        $property = trim($property);

        foreach ($metadata as $id => $map) {
            switch (strtolower($id)) {
                case strtolower($property):
                case strtolower($this->formatter->toCamelCase($property)):
                case strtolower($this->formatter->toUnderscoreCase($property)):
                    return $map;
            }
        }

        return null;
    }

}
