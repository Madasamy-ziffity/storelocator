<?php declare(strict_types=1);

namespace Emizen\Service;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Context;

class StoreLocatorService
{
    private EntityRepository $storeLocatorRepository;

    public function __construct(EntityRepository $storeLocatorRepository)
    {
        $this->storeLocatorRepository = $storeLocatorRepository;
    }

    public function getCities(): array
    {
        $criteria = new Criteria();
        //$criteria->addFilter(new EqualsFilter('active', true));
        //$criteria->addGroupField('city');

        $storeLocators = $this->storeLocatorRepository->search($criteria, Context::createDefaultContext());

        $cities = [];
        foreach ($storeLocators as $storeLocator) {
            $cities[] = $storeLocator->getCity();
        }

        return array_unique($cities);
    }
}
