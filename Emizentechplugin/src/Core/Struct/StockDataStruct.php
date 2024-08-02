<?php
// src/Core/Struct/StockDataStruct.php
namespace Emizen\Core\Struct;

use Shopware\Core\Framework\Struct\Struct;

class StockDataStruct extends Struct
{
    protected $productNumber;
    protected $city;
    protected $stock;

    public function __construct(string $productNumber, string $city, int $stock)
    {
        $this->productNumber = $productNumber;
        $this->city = $city;
        $this->stock = $stock;
    }

    public function getProductNumber(): string
    {
        return $this->productNumber;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getStock(): int
    {
        return $this->stock;
    }
}
