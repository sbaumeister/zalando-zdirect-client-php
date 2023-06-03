<?php

namespace Baumeister\ZalandoClient\Model;

use DateTime;

class StockLocation
{
    public string $id;
    public string $name;
    public string $type;
    public Location $location;
    public string $abbreviation;
    public DateTime $valid_from;
}