<?php
namespace Emizen\Core\Content\Storeinventorystock;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void               add(StoreinventorystockEntity $entity)
 * @method void               set(string $key, StoreinventorystockEntity $entity)
 * @method StoreinventorystockEntity[]    getIterator()
 * @method StoreinventorystockEntity[]    getElements()
 * @method StoreinventorystockEntity|null get(string $key)
 * @method StoreinventorystockEntity|null first()
 * @method StoreinventorystockEntity|null last()
 */
class StoreinventorystockCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return StoreinventorystockEntity::class;
    }
}
