<?php
namespace Emizen\Core\Content\Storeinventorystock;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;

class StoreinventorystockEntity extends Entity
{
    protected string $product_number;
    protected string $city;
    protected string $stock;
    public function getProduct_number(): string
    {
        return $this->product_number;
    }

    public function setProduct_number(string $product_number): void
    {
        $this->product_number = $product_number;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }
    public function getStock(): string
    {
        return $this->stock;
    }

    public function setStock(string $stock): void
    {
        $this->stock = $stock;
    }
}
