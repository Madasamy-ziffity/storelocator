<?php
namespace Emizen\Core\Content\Storelocator;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
* @method void               add(StorelocatorEntity $entity)
* @method void               set(string $key, StorelocatorEntity $entity)
* @method StorelocatorEntity[]    getIterator()
* @method StorelocatorEntity[]    getElements()
* @method StorelocatorEntity|null get(string $key)
* @method StorelocatorEntity|null first()
* @method StorelocatorEntity|null last()
*/
class StorelocatorCollection extends EntityCollection
{
protected function getExpectedClass(): string
{
return StorelocatorEntity::class;
}
}
