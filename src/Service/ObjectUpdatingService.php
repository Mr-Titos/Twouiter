<?php

namespace App\Service;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use ReflectionException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class ObjectUpdatingService
 * Custom service made to dynamically update an object with the data from a request.
 * @package App\Service
 */
class ObjectUpdatingService
{
    private static array $instances = [];
    private Inflector $inflector;
    private PropertyAccessor $propertyAccessor;
    private function __construct() {
        $this->inflector = InflectorFactory::create()->build();
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public static function getInstance(): static
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }
        return self::$instances[$cls];
    }

    /**
     * @param $entityToFill
     * @param $objectStaticProperties
     * Set the data in the entity provided from the static properties object provided by assigning values to matching properties names
     * @return static
     * @throws ReflectionException
     */
    public function fillDataWithMatchingKeyByStaticProperties($entityToFill, $objectStaticProperties) : object {
        $reflection = new \ReflectionClass($objectStaticProperties);
        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            $key = $this->inflector->camelize($property->getName());
            if (property_exists($entityToFill, $key)) {
                $entityToFill->{'set'.ucfirst($key)}($property->getValue($objectStaticProperties));
            }
        }
        return $entityToFill;
    }

    /**
     * @param $entityToFill
     * @param $objectDynamicProperties
     * Set the data in the entity provided from the dynamic properties object provided by assigning values to matching properties names.
     * @return static
     */
    // Note: This method is needed as an object provided by the body of a request doesn't have properties by ReflectionClass
    public function fillDataWithMatchingKeyByDynamicProperties($entityToFill, $objectDynamicProperties) : object {
        foreach ($objectDynamicProperties as $key => $value) {
            $key = $this->inflector->camelize($key);
            if (property_exists($entityToFill, $key)) {
                $entityToFill->{'set'.ucfirst($key)}($this->propertyAccessor->getValue($objectDynamicProperties, $key));
            }
        }
        return $entityToFill;
    }

    /**
     * @param $updatedEntity
     * @param $actualEntity
     * Set the original missing data in the updated entity from the original entity in the database.
     * Permit to update only the fields that are present in the request 👍
     * @return static
     * @throws ReflectionException
     */
    public function fillMissingDataWithOriginalEntity($updatedEntity, $actualEntity) : object {
        $reflection = new \ReflectionClass($updatedEntity);
        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            $key = $property->getName();
            if ($property->getValue($updatedEntity) != null) {
                $key = $this->inflector->camelize($key);
                $actualEntity->{'set'.ucfirst($key)}($updatedEntity->{'get'.ucfirst($key)}());
            }
        }
        return $actualEntity;
    }
}