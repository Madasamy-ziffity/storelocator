<?php declare(strict_types=1);

namespace Emizen\Storefront\Controller;

use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Storefront\Controller\StorefrontController;
use Emizen\Service\StoreLocatorService;
use Symfony\Component\HttpFoundation\Request;


#[Route(defaults: ['_routeScope' => ['storefront']])]
class CustomPageController extends StorefrontController
{
    private StoreLocatorService $storeLocatorService;

    public function __construct(StoreLocatorService $storeLocatorService)
    {
        $this->storeLocatorService = $storeLocatorService;
    }
    #[Route(path: '/custom-page', name: 'frontend.custom.page', methods: ['GET'], defaults: ['XmlHttpRequest' => 'true'])]
    public function show(): Response
    {
        $cities = $this->storeLocatorService->getCities();
        return $this->renderStorefront('@Emizen/storefront/page/custom-page.html.twig', ['cities' => $cities]);
    }
    #[Route(path: '/store-details', name: 'frontend.store.details', methods: ['GET'], defaults: ['XmlHttpRequest' => 'true'])]
    public function details(Request $request): Response
    {
        $city = $request->query->get('city');
        return $this->renderStorefront('@Emizen/storefront/page/store-details.html.twig', ['city' => $city]);
    }
}
