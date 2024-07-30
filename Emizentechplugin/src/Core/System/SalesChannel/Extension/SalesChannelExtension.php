<?php declare(strict_types=1);

namespace Emizen\Core\System\SalesChannel\Extension;

use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\SalesChannel\SalesChannelDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Emizen\Core\Content\Storelocator\StorelocatorDefinition;

class SalesChannelExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            new FkField('storelocator_id', 'storelocatorId', StorelocatorDefinition::class)
        );

        $collection->add(
            new ManyToOneAssociationField('storelocator', 'storelocator_id', StorelocatorDefinition::class, 'id', false)
        );
    }

    public function getDefinitionClass(): string
    {
        return SalesChannelDefinition::class;
    }
}
