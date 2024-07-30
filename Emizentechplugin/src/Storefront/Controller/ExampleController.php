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
        $salesChannelId = $request->query->get('salesChannelId');
        $salesChannelId = hex2bin($salesChannelId);
        $productId = $request->query->get('productId');
        $productId = hex2bin($productId);

        $pre_sql = '
                SELECT 
                    st.lat,
                    st.long
                FROM 
                    sales_channel sc
                LEFT JOIN 
                    storelocator st ON st.id = sc.storelocator_id
                WHERE 
                    sc.id = :salesChannelId
            ';
        $result_sc = $this->connection->fetchAssociative($pre_sql, [
            'salesChannelId' => $salesChannelId
        ]);
        //$this->logger->info('current store: ',$result_sc);
        $currentLat = (float) $result_sc['lat'];
        $currentLong = (float) $result_sc['long'];
        try {
            $sql = '
                SELECT 
                    HEX(cps.sales_channel_id) as sales_channel_id, 
                    cps.stock,sl.city,sl.lat,sl.long
                FROM 
                    custom_product_stock cps 
                LEFT JOIN 
                    sales_channel sc ON sc.id = cps.sales_channel_id 
                LEFT JOIN 
                    storelocator sl ON sl.id = sc.storelocator_id 
                WHERE 
                    cps.product_id = :productId 
            ';
            $result = $this->connection->fetchAllAssociative($sql, [
                'productId' => $productId
            ]);
            foreach($result as &$val){
                $lat = (float) $val['lat'];
                $long = (float) $val['long'];
                $distance = $this->getDistance($currentLat, $currentLong, $lat, $long, 'M');
                $val['distance'] = round($distance).' miles away';
            }
            unset($val); // Break the reference with the last element


            return new JsonResponse(['data' => $result]);
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
                //$this->logger->info($miles);
                return $miles;
            }
        }
    }
}