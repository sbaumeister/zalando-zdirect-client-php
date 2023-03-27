<?php

namespace Baumeister\ZalandoClient\Model;

class ProductSubmission implements \JsonSerializable
{
    private string $outline;
    private ProductModel $product_model;

    public function setOutline(string $outline): ProductSubmission
    {
        $this->outline = $outline;
        return $this;
    }

    public function setProductModel(ProductModel $product_model): ProductSubmission
    {
        $this->product_model = $product_model;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}