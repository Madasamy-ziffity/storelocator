<?php declare(strict_types=1);

namespace Emizen\Controller;

use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StoreLocatorController extends StorefrontController
{
    /**
     * @RouteScope(scopes={"storefront"})
     * @Route("/storelocator/{productId}", name="frontend.storelocator.page", methods={"GET"})
     */
    public function index(Request $request, string $productId): Response
    {
        // Logic to handle the request and find nearby stores
        // For demonstration purposes, we will return a simple response

        return $this->renderStorefront('@Storefront/storefront/page/storelocator/index.html.twig', [
            'productId' => $productId,
        ]);
    }
}
