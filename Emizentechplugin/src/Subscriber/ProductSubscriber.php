<?php
namespace Emizen\Subscriber;

use Shopware\Core\Content\Product\ProductEvents;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;

class ProductSubscriber implements EventSubscriberInterface
{
    private $connection;
    private $logger;

    public function __construct(Connection $connection,LoggerInterface $logger)
    {
        $this->connection = $connection;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
        ProductEvents::PRODUCT_WRITTEN_EVENT => 'onProductWritten'
        ];
    }

    public function onProductWritten(EntityWrittenEvent $event): void
    {
            foreach ($event->getWriteResults() as $result) {
                $payload = $result->getPayload();
                $stock = $payload['stock'] ?? null;
                $productId = $payload['id'] ?? null;

                if ($stock !== null && $productId !== null) {
                    $binaryProductId = hex2bin($productId);

                    $salesChannelId = $this->getSalesChannelIdForProduct($binaryProductId);
                    foreach ($salesChannelId as $sId) {

                        // Save stock data to custom table
                            $this->connection->executeStatement(
                                'INSERT INTO custom_product_stock (id, product_id, sales_channel_id, stock, created_at, updated_at)
                        VALUES (UUID_TO_BIN(UUID()), :productId, :salesChannelId, :stock, NOW(), NOW())
                        ON DUPLICATE KEY UPDATE stock = :stock, updated_at = NOW()',
                            [
                                'productId' => $binaryProductId,
                                'salesChannelId' => hex2bin($sId),
                                'stock' => $stock,
                            ]
                        );
                    }
                }
            }
    }
    private function getSalesChannelIdForProduct(string $productId): ?array
    {
        // Execute a query to fetch sales_channel_id(s) based on product_id
        $query = 'SELECT HEX(sales_channel_id) as sales_channel_id  FROM product_visibility WHERE product_id = :productId';
        $salesChannelIds = $this->connection->fetchAllAssociative($query, [
            'productId' => $productId
        ]);
       // Extract sales_channel_id from the result
        return array_column($salesChannelIds, 'sales_channel_id');
    }
}
