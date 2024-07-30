<?php declare(strict_types=1);

namespace Emizen\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Migration\MigrationStep;

/**
 * @internal
 */
#[Package('core')]
class Migration1721326107createstorelocator extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1721326107;
    }

    public function update(Connection $connection): void
    {
        $query = <<<SQL
           CREATE TABLE `storelocator` (
    `id` BINARY(16) NOT NULL,
    `city` VARCHAR(255) NOT NULL,
    `lat` VARCHAR(255) NOT NULL,
    `long` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

SQL;
        $connection->executeStatement($query);
    }
}
