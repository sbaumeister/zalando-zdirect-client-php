<?php

namespace Baumeister\ZalandoClient\Model;

class ProductConfig implements \JsonSerializable
{
    private string $merchant_product_config_id;
    private array $product_config_attributes;
    /** @var ProductSimple[] */
    private array $product_simples;

    public function setMerchantProductConfigId(string $merchant_product_config_id): ProductConfig
    {
        $this->merchant_product_config_id = $merchant_product_config_id;
        return $this;
    }

    public function setProductConfigAttributes(array $product_config_attributes): ProductConfig
    {
        $this->product_config_attributes = $product_config_attributes;
        return $this;
    }

    /**
     * @param  ProductSimple[]  $product_simples
     */
    public function setProductSimples(array $product_simples): ProductConfig
    {
        $this->product_simples = $product_simples;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}