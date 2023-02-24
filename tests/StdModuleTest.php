<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RobinTheHood\ModifiedStdModule\Classes\StdModule;

final class StdModuleTest extends TestCase
{
    private static array $moduleNames = [
        'MODULE_SYSTEM_MC_MY_FIRST_MODULE_WHICH_DOESNT_EXIST',
        'MODULE_EXPORT_MC_MY_FIRST_MODULE_WHICH_DOESNT_EXIST',
        'MODULE_SHIPPING_MCMYFIRSTMODULE_WHICH_DOESNT_EXIST',
        'MODULE_PAYMENT_MC_MY_FIRST_MODULE_WHICH_DOESNT_EXIST',
        'MODULE_ORDER_TOTAL_MC_MY_FIRST_MODULE_WHICH_DOESNT_EXIST',
    ];

    public function testIsEnabled(): void
    {
        foreach (self::$moduleNames as $moduleName) {
            $isEnabled = StdModule::isEnabled($moduleName);

            $this->assertFalse($isEnabled);
        }
    }

    public function testIsDisabled(): void
    {
        foreach (self::$moduleNames as $moduleName) {
            $isDisabled = StdModule::isDisabled($moduleName);

            $this->assertTrue($isDisabled);
        }
    }
}
