<?php

use RobinTheHood\ModifiedStdModule\Classes\StdModule;

// Normaly the autload.php is loaded by the composer/autoload, but we do not know
// the file order in /includes/extra/functions/. So if this file is loaded first,
// the composer/autoload dose not loaded the autoload.php already and we have to
// to load autoload.php by ourself.
require_once DIR_FS_DOCUMENT_ROOT . '/vendor-no-composer/autoload.php';

if (!function_exists('rth_is_module_enabled')) {
    function rth_is_module_enabled(string $moduleName = ''): bool
    {
        return StdModule::isEnabled($moduleName);
    }
}

if (!function_exists('rth_is_module_disabled')) {
    function rth_is_module_disabled(string $moduleName = ''): bool
    {
        return StdModule::isDisabled($moduleName);
    }
}
