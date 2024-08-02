<?php
namespace Emizen\Controller;

use Emizen\Service\StoreCityService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

    class StoreCityController
    {
        private $storeCityService;

        public function __construct(StoreCityService $storeCityService)
        {
            $this->storeCityService = $storeCityService;
        }

        /**
        * @Route("/store-city", name="frontend.store.city", methods={"GET"})
        */
        public function getStoreCity(): Response
        {
            $storeCity = $this->storeCityService->getStoreCity();
            return new Response('Store City: ' . $storeCity);
        }
    }
