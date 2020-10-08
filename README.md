# Library Module: Modified Standard Module
(DE): Programmcodeerweiterung: Standard Modul für Modified 

With this library you need less code to write system and class extensions for modified.

## Requirements
PHP 7.0 or above

## Authors
- 2020 by Robin Wieschendorf

## Contributing
We would be happy if you would like to take part in the development of this module. If you wish more features or you want to make improvements or to fix errors feel free to contribute. In order to contribute, you just have to fork this repository and make pull requests.


## Usage

### Class: StdModule
This is a example of how to use the StdModule class vor a System Module.

`admin/includes/modules/system/mc_my_first_module.php`

```php
defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

use RobinTheHood\ModifiedStdModule\Classes\StdModule;
require_once DIR_FS_DOCUMENT_ROOT . '/vendor-no-composer/autoload.php';

class mc_my_first_module extends StdModule
{
    public function __construct()
    {
        $this->init('MODULE_MC_MY_FIRST_MODULE');
    }

    public function display()
    {
        return $this->displaySaveButton();
    }

    public function install()
    {
        parent::install();
    }

    public function remove()
    {
        parent::remove();
    }
}
```

If you want add access to a file you can use `$this->setAdminAccess()` oder `$this->deleteAdminAccess()`

```php
...
public function install()
{
    parent::install();
    $this->setAdminAccess('mc_my_first_module');
}
...
public function remove()
{
    parent::remove();
    $this->deleteAdminAccess('mc_my_first_module');
}
...
```

### Class: Configuration
In order to use configuration values you can use them as usual:

```php
echo MODULE_MC_MY_FIRST_MODULE_MY_CONFIGURATION_VALUE_1;
echo MODULE_MC_MY_FIRST_MODULE_MY_CONFIGURATION_VALUE_2;
echo MODULE_MC_MY_FIRST_MODULE_MY_CONFIGURATION_VALUE_2;
```

Or you can use the helper class `Configuration`:

```php
use RobinTheHood\ModifiedStdModule\Classes\Configuration;

$config = new Configuration('MODULE_MC_MY_FIRST_MODULE');

echo $config->myConfigurationValue1;
echo $config->myConfigurationValue2;
echo $config->myConfigurationValue3;
```
