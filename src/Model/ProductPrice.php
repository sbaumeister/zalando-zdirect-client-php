<?php

namespace Baumeister\ZalandoClient\Model;

class ProductPrice
{
    public string $ean;
    public string $sales_channel_id;
    public RegularPrice $regular_price;
}