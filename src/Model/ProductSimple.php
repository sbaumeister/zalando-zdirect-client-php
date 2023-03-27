<?php

namespace Baumeister\ZalandoClient\Model;

class ProductSimple implements \JsonSerializable
{
    private string $merchant_product_simple_id;
    private array $product_simple_attributes;

    public function setMerchantProductSimpleId(string $merchant_product_simple_id): ProductSimple
    {
        $this->merchant_product_simple_id = $merchant_product_simple_id;
        return $this;
    }

    public function setProductSimpleAttributes(array $product_simple_attributes): ProductSimple
    {
        $this->product_simple_attributes = $product_simple_attributes;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}