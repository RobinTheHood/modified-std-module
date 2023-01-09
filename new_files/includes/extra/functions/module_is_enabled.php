<?php

use RobinTheHood\ModifiedStdModule\Classes\StdModule;

require_once DIR_FS_DOCUMENT_ROOT . '/vendor-no-composer/autoload.php';

/**
 * Determine if a module is enabled.
 *
 * @param string $module The case-sensitive module name (i. e.
 * "MODULE_MY_FIRST_MODULE")
 *
 * @return boolean
 */
function module_is_enabled(string $module): bool
{
    $std_module        = new StdModule($module);
    $module_is_enabled = $std_module->getEnabled();

    return $module_is_enabled;
}
