<?php

namespace Baumeister\ZalandoClient\Model;

class ItemQuantity
{
    public string $ean;
    public int $total_quantity;
    public QuantitiesByState $quantities_by_state;
    /** @var QuantitiesByLocation[] */
    public array $quantities_by_location;
}