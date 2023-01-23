<?php

namespace Baumeister\ZalandoClient\Model;

class AttributeValue
{
    public bool $boolean;
    public \DateTime $datetime;
    public float $decimal;
    public int $integer;
    public string $string;
    public LocalizedValue $localized;

}