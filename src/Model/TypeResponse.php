<?php

namespace Baumeister\ZalandoClient\Model;

class TypeResponse
{
    public string $label;
    public Name $name;
    public Description $description;
    public TypeCardinality $cardinality;
    public TypeDefinition $definition;
    public TypeUsage $usage;
    /** @var TypeVariant[] */
    public array $type_variants;
}