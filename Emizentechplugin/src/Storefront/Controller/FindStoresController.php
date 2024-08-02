<?php declare(strict_types=1);

namespace Emizen\Storefront\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class FindStoresController extends StorefrontController
{
    private $connection;
    private $storeLocatorRepository;
    public function __construct(Connection $connection,EntityRepository $storeLocatorRepository)
    {
        $this->connection = $connection;
        $this->storeLocatorRepository = $storeLocatorRepository;
    }
    #[Route(path: '/find-stores', name: 'frontend.find.stores', methods: ['GET'], defaults: ['XmlHttpRequest' => 'true'])]
    public function findStores(Request $request): JsonResponse
    {
        $city = $request->query->get('city');
        try {
            $sql = '
                SELECT 
                    cps.city,cps.lat,cps.long
                FROM 
                    storelocator cps
                WHERE 
                    cps.city =:city
            ';
            $result = $this->connection->fetchAllAssociative($sql, [
                'city' => $city
            ]);
            return new JsonResponse(['data' => $result]);
        }
        catch (\Exception $e) {
            $this->logger->error('Query failed: ' . $e->getMessage());
            return new JsonResponse(['error' => 'An error occurred'], 500);
        }
    }
    #[Route(path: '/store-locations', name: 'frontend.store.locations', methods: ['GET'], defaults: ['XmlHttpRequest' => 'true'])]
    public function getStoreLocations(Request $request): JsonResponse
    {
        $city = $request->query->get('city');
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('city', $city));
        $locations = $this->storeLocatorRepository->search($criteria, Context::createDefaultContext())->getEntities();

        $data = [];
        foreach ($locations as $location) {
            $data[] = [
                'city' => $location->getCity(),
                'lat' => $location->getLat(),
                'lng' => $location->getLong(),
            ];
        }

        return new JsonResponse($data);
    }
}