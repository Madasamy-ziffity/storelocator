<?php declare(strict_types=1);

namespace Emizen\Storefront\Controller;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\System\SystemConfig\SystemConfigService;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class MystoresController extends StorefrontController
{
    private SystemConfigService $systemConfigService;

    public function __construct(SystemConfigService $systemConfigService)
    {
        $this->systemConfigService = $systemConfigService;
    }
    #[Route(path: '/mystores', name: 'frontend.my.stores', methods: ['GET'], defaults: ['XmlHttpRequest' => 'true'])]
    public function myStores(Request $request): JsonResponse
    {
        $city = $request->query->get('city');
        try {
            // Update the store city configuration value
            $this->systemConfigService->set('Emizentechplugin.config.storeCity', $city);
            $this->addFlash('success', 'Store city updated successfully.');
            return new JsonResponse(['success' => true, 'message' => 'Store city updated successfully.']);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'An error occurred while updating the store city.'], 500);
        }
    }
}