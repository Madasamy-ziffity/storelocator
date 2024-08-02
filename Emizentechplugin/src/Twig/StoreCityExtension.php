<?php
namespace Emizen\Twig;

use Shopware\Core\System\SystemConfig\SystemConfigService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StoreCityExtension extends AbstractExtension
{
    private $systemConfigService;

    public function __construct(SystemConfigService $systemConfigService)
    {
        $this->systemConfigService = $systemConfigService;
    }

    public function getFunctions(): array
    {
        return [
        new TwigFunction('getStoreCity', [$this, 'getStoreCity'])
        ];
    }

    public function getStoreCity(): string
    {
        return $this->systemConfigService->get('Emizentechplugin.config.storeCity') ?? '';
    }
}
