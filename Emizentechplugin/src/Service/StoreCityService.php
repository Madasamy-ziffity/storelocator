<?php
namespace Emizen\Service;

use Shopware\Core\System\SystemConfig\SystemConfigService;

    class StoreCityService
    {
        private $systemConfigService;

        public function __construct(SystemConfigService $systemConfigService)
        {
            $this->systemConfigService = $systemConfigService;
        }

        public function getStoreCity(): string
        {
            return $this->systemConfigService->get('Emizentechplugin.config.storeCity') ?? '';
        }
    }
