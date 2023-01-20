<?php

namespace Baumeister\ZalandoClient\Model;

use DateTime;

class OrderAttributes
{
    const STATUS_INITIAL = 'initial';
    const STATUS_APPROVED = 'approved';
    const STATUS_FULFILLED = 'fulfilled';

    const TYPE_ZALANDO = 'ZalandoFulfilled';
    const TYPE_PARTNER = 'PartnerFulfilled';

    public string $order_id;
    public string $order_number;
    public string $customer_number;
    public string $customer_email;
    public string $order_type;
    public string $status;
    public bool $exported;
    public Address $shipping_address;
    public Address $billing_address;
    public int $order_lines_count;
    public float $order_lines_price_amount;
    public string $order_lines_price_currency;
    public Money $shipping_costs;
    public DateTime $order_date;
    public DateTime $created_at;
}