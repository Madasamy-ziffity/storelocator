<?php declare(strict_types=1);

namespace Emizen\Storefront\Controller;

use Doctrine\DBAL\Connection;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class ExampleController extends StorefrontController
{
    private $connection;
    private $logger;
    public function __construct(Connection $connection,LoggerInterface $logger)
    {
        $this->connection = $connection;
        $this->logger = $logger;
    }
    #[Route(path: '/example', name: 'frontend.example.example', methods: ['GET'], defaults: ['XmlHttpRequest' => 'true'])]
    public function showExample(Request $request): JsonResponse
    {
        //$this->logger->info('testing');
        $productNumber = $request->query->get('productId');

        $currentLat=(float)'42.530016';
        $currentLong=(float)'-83.363174';
        try {
            $sql = '
                SELECT 
                    cps.stock,cps.city,st.lat,st.long
                FROM 
                    store_inventory_stock cps
                LEFT JOIN storelocator st on st.city = cps.city
                WHERE 
                    cps.product_number = :productNumber
            ';
            $result = $this->connection->fetchAllAssociative($sql, [
                'productNumber' => $productNumber
            ]);
            //$this->logger->info('result',$result);
            $filteredResult = [];
            foreach($result as &$val){
                $lat = (float) $val['lat'];
                $long = (float) $val['long'];
                $distance = $this->getDistance($currentLat, $currentLong, $lat, $long, 'M');
                if ($distance <= 90) {
                    $val['distance'] = round($distance);
                    $filteredResult[] = $val;
                }
            }
            unset($val); // Break the reference with the last element
            // Sort the filtered result by distance in ascending order
            usort($filteredResult, function ($a, $b) {
                return $a['distance'] <=> $b['distance'];
            });

            // Convert distance back to string format with "miles away"
            foreach ($filteredResult as &$val) {
                $val['distance'] = $val['distance'] . ' miles away';
            }

            return new JsonResponse(['data' => $filteredResult]);
        } catch (\Exception $e) {
            $this->logger->error('Query failed: ' . $e->getMessage());
            return new JsonResponse(['error' => 'An error occurred'], 500);
        }

    }
    private function getDistance($lat1, $lon1, $lat2, $lon2, $unit){
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        }
        else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }
}