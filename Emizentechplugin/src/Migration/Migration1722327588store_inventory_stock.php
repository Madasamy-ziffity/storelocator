<?php declare(strict_types=1);

namespace Emizen\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Migration\MigrationStep;

/**
 * @internal
 */
#[Package('core')]
class Migration1722327588store_inventory_stock extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1722327588;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE `store_inventory_stock` (
    `id` BINARY(16) NOT NULL,
    `product_number` VARCHAR(64) NOT NULL,
    `city` VARCHAR(64) NOT NULL,
    `stock` INT NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_product_storelocator` (`product_number`, `city`),
    CONSTRAINT `fk_product_store_stock_product_id` FOREIGN KEY (`product_number`)
        REFERENCES `product` (`product_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 '
        );

    }
}
