# Library Module: Modified Standard Module
[![dicord](https://img.shields.io/discord/727190419158597683)](https://discord.gg/9NqwJqP)

(DE): Programmcodeerweiterung: Standard Modul für Modified 

With this library you need less code to write system and class extensions for modified. If you need help visit the [Discord Server](https://discord.gg/9NqwJqP) for MMLC.

## Requirements
- PHP 7.0 or above
- [MMLC](https://module-loader.de) *(recommended)*

## Authors
- 2020 by Robin Wieschendorf

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

### Add Configuration
You can add a configuration with a short KEY. Internaly the KEY will be prefixed with your init() moduleName configuration. Example: Is your init() configuration is `MODULE_MC_MY_FIRST_MODULE` and your configuration key is `USER_NAME` the whole configuration name is `MODULE_MC_MY_FIRST_MODULE_USER_NAME`.

You can use the following methods in the `public StdModule::install()` method.

```php
// Example
public __construct() {
    $parent::__construct('MODULE_MC_MY_FIRST_MODULE');
    $this->addKey('USER_NAME');
}

public function install()
{
    parent::install();
    $this->addConfiguration('USER_NAME', 'default', 6, 1);
}
```

Most of the addConfiguration methods can handle the following parameters:

- `key` - Configuration key name
- `value` - Default value
- `groupId` - Shoudt be `6` for system modules
- `sortOrder` - Sort position of the configuration entry

#### Add a textfield configuration
```php
public StdModule::addConfiguration(string $key, string $value, int $groupId, int $sortOrder): void

// Example
$this->addConfiguration('USER_NAME', 'default', 6, 1);
```

#### Adds a YES | NO configuration
```php
public StdModule::addConfigurationSelect(string $key, bool $value, int $groupId, int $sortOrder): void

// Example
$this->addConfigurationSelect('SHOW_LASTNAME', true, 6, 1);
```

#### Adds a textfield configuration
```php
public StdModule::addConfigurationTextArea(string $key, string $value, int $groupId, int $sortOrder): void

// Example
$this->addConfigurationTextArea('IMPRINT', 'a longer text', 6, 1);
```

#### Adds a order status select configuration
```php
public StdModule::addConfigurationOrderStatus(string $key, int $value, int $groupId, int $sortOrder): void

// Example
$this->addConfigurationTextArea('CHANGE_TO_STATUS', 2, 6, 1);
```

#### Adds a select with multible options
*Notice: option-id/name and option-value are equal. You can add a translation in the language file.*
```php
public StdModule::addConfigurationDropDown(string $key, string $value, int $groupId, int $sortOrder, array $values): void

// Example
$this->addConfigurationDropDown('MODE', 'a', 6, 1, ['a', 'b', 'c']);
```

#### Adds a select with multible options from static function
*Notice: option-id/name and option-value are equal. You can add a translation in the language file.*

```php
public StdModule::addConfigurationDropDownByStaticFunction(string $key, string $value, int $groupId, int $sortOrder, string $staticCallFunctionName): void

// Example
public static function aStaticMethod()
{
    return ['a', 'b', 'c', 'd'];
}

$this->addConfigurationDropDownByStaticFunction('MODE', 'a', 6, 1, 'aStaticMethod');
```

### Delete configuration

```php
public StdModule::deleteConfiguration(string $key): void

// Example
$this->deleteConfiguration('STATUS');
```

### Adding an action and defining a method in a module class

**Note:** Currently this only works for System and Export modules.

In the overview of the modules in the admin area, you can add additional buttons to the right under the description of your module and an action that should be carried out when you click on the button.

1. Open the file that contains the class where you want to add the module class action. For example: `...modules/system/mc_my_module.php`

2. In the constructor of the class, add the following code to register the action:

    ```php
    public __construct() {
       ...
       $this->addAction('myMethod', 'My Button');
       ...
    }
    ```

    This line of code registers an action named `myMethod` with the label 'My Button'.

3. Inside the module class, create a method named `invokeMyMethod` with the following code:

    ```php
    public function invokeMyMethod()
    {
        ...
    }
    ```

    This method will be called when the action `myMethod` is triggered.

That's it! You have now added an action and defined the corresponding method in your module class. Make sure to fill in the necessary code within the `invokeMyMethod` method to achieve the desired functionality.

### Easy access with class Configuration
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

## Contributing
We would be happy if you would like to take part in the development of this module. If you wish more features or you want to make improvements or to fix errors feel free to contribute. In order to contribute, you just have to fork this repository and make pull requests.

### Coding Style
We are using:
- [PSR-1: Basic Coding Standard](https://www.php-fig.org/psr/psr-1/)
- [PSR-12: Extended Coding Style](https://www.php-fig.org/psr/psr-12/)

### Version and Commit-Messages
We are using:
- [Semantic Versioning 2.0.0](https://semver.org)
- [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/)

### Testing

```bash
composer install
./vendor/bin/phpunit tests --testdox --colors
```
