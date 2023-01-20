<?php

namespace Baumeister\ZalandoClient\Model;

class OrderItemAttributes
{
    public string $order_item_id;
    public string $order_id;
    public string $article_id;
    public string $external_id;
    public string $description;
    public int $quantity_initial;
}