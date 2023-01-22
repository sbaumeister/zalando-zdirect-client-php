<?php

namespace Baumeister\ZalandoClient\Model;

class OutlineTier
{
    /** @var string[]  */
    public array $mandatory_types;
    /** @var string[]  */
    public array $optional_types;
    /** @var AttributePerType[] */
    public array $restricted_attributes;
}