<?php

namespace Baumeister\ZalandoClient\Model;

class OrdersTopLevel
{
    /** @var Order[] */
    public array $data;
    public Meta $meta;
    /** @var ResourceObject[] */
    public array $included;
}