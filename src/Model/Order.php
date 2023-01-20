<?php

namespace Baumeister\ZalandoClient\Model;

class Order
{
    public string $id;
    public string $type;
    public OrderAttributes $attributes;
    public OrderRelationships $relationships;
}