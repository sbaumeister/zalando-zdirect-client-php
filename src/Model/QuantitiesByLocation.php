<?php

namespace Baumeister\ZalandoClient\Model;

class QuantitiesByLocation
{
    public string $stock_location_id;
    public QuantitiesByState $quantities_by_state;
}