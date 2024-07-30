<?php
namespace Emizen\Core\Content\Storelocator;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\UpdatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;

class StorelocatorDefinition extends EntityDefinition
{
public const ENTITY_NAME = 'storelocator';

public function getEntityName(): string
{
return self::ENTITY_NAME;
}

public function getCollectionClass(): string
{
return StorelocatorCollection::class;
}

public function getEntityClass(): string
{
return StorelocatorEntity::class;
}

protected function defineFields(): FieldCollection
{
return new FieldCollection([
(new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
(new StringField('city', 'city'))->addFlags(new Required()),
(new StringField('lat', 'lat'))->addFlags(new Required()),
(new StringField('long', 'long'))->addFlags(new Required()),
new CreatedAtField(),
new UpdatedAtField(),
]);
}
}
