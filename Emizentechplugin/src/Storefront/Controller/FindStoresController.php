<?php declare(strict_types=1);

namespace Emizen\Storefront\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\DBAL\Connection;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class FindStoresController extends StorefrontController
{
    private $connection;
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
    #[Route(path: '/find-stores', name: 'frontend.find.stores', methods: ['GET'], defaults: ['XmlHttpRequest' => 'true'])]
    public function findStores(Request $request): JsonResponse
    {
        $city = $request->query->get('city');
        try {
            $sql = '
                SELECT 
                    cps.city
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
}