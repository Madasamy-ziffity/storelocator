<?php declare(strict_types=1);

namespace Emizen\Storefront\Controller;

use Doctrine\DBAL\Connection;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use Emizen\Service\StoreCityService;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class ExampleController extends StorefrontController
{
    private $connection;
    private $logger;
    private $storeCityService;
    public function __construct(Connection $connection,LoggerInterface $logger,StoreCityService $storeCityService)
    {
        $this->connection = $connection;
        $this->logger = $logger;
        $this->storeCityService = $storeCityService;
    }
    #[Route(path: '/example', name: 'frontend.example.example', methods: ['GET'], defaults: ['XmlHttpRequest' => 'true'])]
    public function showExample(Request $request): JsonResponse
    {
        //$this->logger->info('testing');
        $productNumber = $request->query->get('productId');
        $dafaultCityData = $this->getDefaultcity();

        $currentLat=(float)$dafaultCityData['lat'];
        $currentLong=(float)$dafaultCityData['long'];
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
                $distance = round($this->getDistance($currentLat, $currentLong, $lat, $long, 'M'));
                if ($distance <= 90 && $val['stock'] >0) {
                    $val['distance'] = $distance;
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
    #[Route(path: '/radius-stores', name: 'frontend.radius.stores', methods: ['GET'], defaults: ['XmlHttpRequest' => 'true'])]
    public function radiusStores(Request $request): JsonResponse
    {
        $radius = $request->query->get('radius');
        $dafaultCityData = $this->getDefaultcity();

        $currentLat=(float)$dafaultCityData['lat'];
        $currentLong=(float)$dafaultCityData['long'];

        try {
            $sql = '
                SELECT 
                    cps.city,cps.lat,cps.long
                FROM 
                    storelocator cps';
            $result = $this->connection->fetchAllAssociative($sql);
            $filteredResult = [];
            foreach($result as &$val){
                $lat = (float) $val['lat'];
                $long = (float) $val['long'];
                $distance = round($this->getDistance($currentLat, $currentLong, $lat, $long, 'M'));
                if ($distance <= $radius) {
                    $val['distance'] = $distance;
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
        }catch (\Exception $e) {
            $this->logger->error('Query failed: ' . $e->getMessage());
            return new JsonResponse(['error' => 'An error occurred'], 500);
        }

        //return new JsonResponse(['data' => $radius]);
    }
    private function getDefaultcity(){
        $city = $this->storeCityService->getStoreCity();
        $latlongSql = '
                SELECT 
                    cps.lat,cps.long
                FROM 
                    storelocator cps
                WHERE 
                    cps.city =:city
            ';
        $latlongResult = $this->connection->fetchAssociative($latlongSql, [
            'city' => $city
        ]);
        return $latlongResult;
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