<?php

namespace Baumeister\ZalandoClient\Model;

class OrderLineAttributes
{
    const STATUS_INITIAL = 'initial';
    const STATUS_RESERVED = 'reserved';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_RETURNED = 'returned';
    const STATUS_CANCELED = 'canceled';

    public string $order_line_id;
    public string $order_item_id;
    public string $status;
    public Money $price;
}