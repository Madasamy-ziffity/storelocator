<?php declare(strict_types=1);

namespace Emizen\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Migration\MigrationStep;

/**
 * @internal
 */
#[Package('core')]
class Migration1721735666custom_product_stock extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1721735666;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE `custom_product_stock` (
    `id` BINARY(16) NOT NULL,
    `product_id` BINARY(16) NOT NULL,
    `sales_channel_id` BINARY(16) NOT NULL,
    `stock` INT NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_product_sales_channel` (`product_id`, `sales_channel_id`),
    CONSTRAINT `fk_product_stock_product_id` FOREIGN KEY (`product_id`)
        REFERENCES `product` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; '
        );
    }
}
