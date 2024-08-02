<?php

namespace Emizen\Core\Subscriber;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Emizen\Core\Service\StockService;
use Emizen\Core\Struct\StockDataStruct;
use Shopware\Core\Framework\Struct\ArrayStruct;

class ProductSubscriber implements EventSubscriberInterface
{
    private $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'product.loaded' => 'onProductLoaded'
        ];
    }

    public function onProductLoaded(EntityLoadedEvent $event): void
    {
        foreach ($event->getEntities() as $product) {
            $productNumber = $product->get('productNumber');
            $stockData = $this->stockService->getStockByProductNumber($productNumber);
            $product->addExtension('stockData', new ArrayStruct($stockData));
        }
    }
}
