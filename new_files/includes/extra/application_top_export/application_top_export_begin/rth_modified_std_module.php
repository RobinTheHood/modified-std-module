<?php

use RobinTheHood\ModifiedStdModule\Classes\StdModule;

/**
 * Usually the `composer_autoload.php` is loaded by the `composer/autoload`
 * module. Since we do not know the file order in `/includes/extra/functions/`,
 * we will make sure the `autoload.php` is loaded before this file.
 */
require_once DIR_FS_DOCUMENT_ROOT . '/includes/extra/functions/composer_autoload.php';

if (!function_exists('rth_is_module_enabled')) {
    function rth_is_module_enabled(string $moduleName): bool
    {
        return StdModule::isEnabled($moduleName);
    }
}

if (!function_exists('rth_is_module_disabled')) {
    function rth_is_module_disabled(string $moduleName): bool
    {
        return StdModule::isDisabled($moduleName);
    }
}
