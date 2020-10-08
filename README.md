# Library Module: Modified Standard Module
(DE): Programmcodeerweiterung: Standard Modul für Modified 

With this library you need less code to write system and class extensions for modified.

## Requirements
PHP 7.0 or above

## Authors
- 2020 by Robin Wieschendorf

## Contributing
We would be happy if you would like to take part in the development of this module. If you wish more features or you want to make improvements or to fix errors feel free to contribute. In order to contribute, you just have to fork this repository and make pull requests or become a member of the github organisation [ModifiedCommunityModules](https://github.com/ModifiedCommunityModules) to get direct access to the repository.


## Usage
*Write here usage of class ModifiedStdModule ...*

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
