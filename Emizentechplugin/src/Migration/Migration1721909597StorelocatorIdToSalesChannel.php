<?php declare(strict_types=1);

namespace Emizen\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Migration\MigrationStep;

/**
 * @internal
 */
#[Package('core')]
class Migration1721909597StorelocatorIdToSalesChannel extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1721909597;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            ALTER TABLE `sales_channel`
            ADD COLUMN `storelocator_id` BINARY(16) NULL AFTER `id`,
            ADD CONSTRAINT `fk.storelocator_id` FOREIGN KEY (`storelocator_id`) REFERENCES `storelocator` (`id`) ON DELETE SET NULL;
        ');
    }
}
