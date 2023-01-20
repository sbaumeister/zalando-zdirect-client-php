<?php

namespace Baumeister\ZalandoClient\Model;

class OrderLinesTopLevel
{
    /** @var OrderLine[] */
    public array $data;
    public Meta $meta;
    /** @var ResourceObject[] */
    public array $included;
}