<?php

namespace Baumeister\ZalandoClient\Model;

use DateTime;

class AttributeValue
{
    public ?bool $boolean = null;
    public ?DateTime $datetime = null;
    public ?float $decimal = null;
    public ?int $integer = null;
    public ?string $string = null;
    public ?LocalizedValue $localized = null;
}