<?php

namespace Baumeister\ZalandoClient\Model;

class OrderLine
{
    public string $id;
    public string $type;
    public OrderLineAttributes $attributes;
}