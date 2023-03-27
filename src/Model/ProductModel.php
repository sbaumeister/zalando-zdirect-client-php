<?php

namespace Baumeister\ZalandoClient\Model;

class ProductModel implements \JsonSerializable
{
    private string $merchant_product_model_id;
    private array $product_model_attributes;
    /** @var ProductConfig[] */
    private array $product_configs;

    public function setMerchantProductModelId(string $merchant_product_model_id): ProductModel
    {
        $this->merchant_product_model_id = $merchant_product_model_id;
        return $this;
    }

    public function setProductModelAttributes(array $product_model_attributes): ProductModel
    {
        $this->product_model_attributes = $product_model_attributes;
        return $this;
    }

    public function setProductConfigs(array $product_configs): ProductModel
    {
        $this->product_configs = $product_configs;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}