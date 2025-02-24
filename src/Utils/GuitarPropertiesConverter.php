<?php

namespace App\Utils;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class GuitarPropertiesConverter implements NameConverterInterface
{
    public function normalize(string $propertyName): string
    {
        return ucfirst($propertyName);
    }

    public function denormalize(string $propertyName): string
    {
        return $propertyName;
    }
}
