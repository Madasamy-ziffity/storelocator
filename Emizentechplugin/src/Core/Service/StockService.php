<?php

namespace Emizen\Core\Service;

use Doctrine\DBAL\Connection;
use Emizen\Core\Struct\StockDataStruct;

class StockService
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getStockByProductNumber(string $productNumber): array
    {
        $query = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('store_inventory_stock')
            ->where('product_number = :productNumber')
            ->setParameter('productNumber', $productNumber)
            ->execute();

        $results = $query->fetchAll();
        $stockData = [];

        foreach ($results as $result) {
            $stockData[] = new StockDataStruct($result['product_number'], $result['city'], $result['stock']);
        }

        return $stockData;
    }
}
