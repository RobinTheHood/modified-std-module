<?php

declare(strict_types=1);

namespace RobintTheHood\ModifiedStdModule\Tests;

use PHPUnit\Framework\TestCase;
use RobinTheHood\ModifiedStdModule\Classes\StdModule;

final class StdModuleTest extends TestCase
{
    private static array $moduleNamesActive = [
        'MODULE_SYSTEM_MC_MY_FIRST_ACTIVE_MODULE',
        'MODULE_EXPORT_MC_MY_FIRST_ACTIVE_MODULE',
        'MODULE_SHIPPING_MCMYFIRSTACTIVEMODULE',
        'MODULE_PAYMENT_MC_MY_FIRST_ACTIVE_MODULE',
        'MODULE_ORDER_TOTAL_MC_MY_FIRST_ACTIVE_MODULE',
    ];
    private static array $moduleNamesInactive = [
        'MODULE_SYSTEM_MC_MY_FIRST_INACTIVE_MODULE',
        'MODULE_EXPORT_MC_MY_FIRST_INACTIVE_MODULE',
        'MODULE_SHIPPING_MCMYFIRSTINACTIVEMODULE',
        'MODULE_PAYMENT_MC_MY_FIRST_INACTIVE_MODULE',
        'MODULE_ORDER_TOTAL_MC_MY_FIRST_INACTIVE_MODULE',
    ];

    public static function setUpBeforeClass(): void
    {
        foreach (self::$moduleNamesActive as $moduleName) {
            define($moduleName . '_STATUS', 'true');
        }

        foreach (self::$moduleNamesInactive as $moduleName) {
            define($moduleName . '_STATUS', 'false');
        }
    }

    public function testIsEnabled(): void
    {
        foreach (self::$moduleNamesActive as $moduleName) {
            $isEnabled = StdModule::isEnabled($moduleName);

            $this->assertTrue($isEnabled);
        }

        foreach (self::$moduleNamesInactive as $moduleName) {
            $isEnabled = StdModule::isEnabled($moduleName);

            $this->assertFalse($isEnabled);
        }
    }

    public function testIsDisabled(): void
    {
        foreach (self::$moduleNamesActive as $moduleName) {
            $isDisabled = StdModule::isDisabled($moduleName);

            $this->assertFalse($isDisabled);
        }

        foreach (self::$moduleNamesInactive as $moduleName) {
            $isDisabled = StdModule::isDisabled($moduleName);

            $this->assertTrue($isDisabled);
        }
    }
}
