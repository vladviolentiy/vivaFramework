<?php

namespace VladViolentiy\VivaFramework;

use ReflectionClass;
use VladViolentiy\VivaFramework\Exceptions\ValidationException;

class JSON
{
    /**
     * @param non-empty-string $input
     * @param class-string $structure
     * @param string $errorText
     * @return object
     * @throws ValidationException
     */
    public static function Unmarshal(string $input, string $structure, string $errorText = "Error decode json"): object
    {
        /** @var object|array<mixed,object>|null $data */
        $data = json_decode($input);
        if($data === null) {
            throw new ValidationException($errorText);
        }
        return self::recursiveMethod($data, $structure);
    }

    /**
     * @param object|array<mixed> $jsonDecodedData
     * @param class-string $className
     * @return object
     * @throws ValidationException
     * @throws \ReflectionException
     */
    private static function recursiveMethod(object|array $jsonDecodedData, string $className): object
    {
        if(!is_array($jsonDecodedData)) {
            $jsonDecodedData = get_object_vars($jsonDecodedData);
        }
        $object = new $className();
        foreach ($jsonDecodedData as $key => $value) {
            if(property_exists($object, $key)) {
                if(is_object($value)) {
                    /** @var class-string $type */
                    $type = (string)(new ReflectionClass($object))->getProperty($key)->getType();
                    $object->{$key} = self::recursiveMethod($value, $type);
                } else {
                    $object->{$key} = $value;
                }
            } else {
                throw new ValidationException("Incorrect method");
            }
        }

        return $object;
    }
}
