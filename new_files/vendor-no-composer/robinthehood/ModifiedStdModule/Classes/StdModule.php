<?php

namespace RobinTheHood\ModifiedStdModule\Classes;

class StdModule
{
    public $code;
    public $title;
    public $description;
    public $enabled;
    public $sort_order;

    public $modulePrefix;
    public $keys;

    public function init($modulePrefix, $code = '')
    {
        $this->modulePrefix = $modulePrefix;

        if ($code) {
            $this->code = $code;
        } else {
            $this->code = get_class($this);
        }

        $this->title = $this->getTitle();
        $this->description = $this->getDescription();
        $this->sort_order = $this->getSortOrder();
        $this->enabled = $this->getEnabled();

        $this->addKey('STATUS');
    }

    public function addKey($key)
    {
        $this->keys[] = $this->getModulePrefix() . '_' . $key;
    }

    public function getTitle()
    {
        return $this->getConfig('TITLE');
    }

    public function getDescription()
    {
        return $this->getConfig('LONG_DESCRIPTION');
    }

    public function getSortOrder()
    {
        $sortOrder = $this->getConfig('SORT_ORDER', 0);
         
        return $sortOrder;
    }

    public function getEnabled()
    {
        $status = strtolower($this->getConfig('STATUS'));
        if ($status == 'true') {
            return true;
        }
        return false;
    }

    public function getModulePrefix()
    {
        return $this->modulePrefix;
    }

    public function getConfig($name, $default = '')
    {
        $constantName = $this->getModulePrefix() . '_' . $name;

        return defined($constantName) ? constant($constantName) : $default;
    }

    public function process($file)
    {
    }

    public function display()
    {
    }

    public function displaySaveButton()
    {
        return [
            'text' => '<br><div align="center">' . xtc_button(BUTTON_SAVE) . xtc_button_link(BUTTON_CANCEL, xtc_href_link(FILENAME_MODULE_EXPORT, 'set=' . $_GET['set'] . '&module=' . $this->code)) . '</div>'
        ];
    }

    public function check()
    {
        if (!isset($this->check)) {
            $key = $this->getModulePrefix() . '_STATUS';

            $query = xtc_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = '$key'");
            $this->check = xtc_db_num_rows($query);
        }

        return $this->check;
    }

    public function install()
    {
        $this->addConfigurationSelect('STATUS', 'true', 6, 1);
    }

    public function remove()
    {
        $this->deleteConfiguration('STATUS');
    }

    public function keys()
    {
        return $this->keys;
    }

    public function addConfigurationSelect($key, $value, $groupId, $sortOrder)
    {
        $this->addConfiguration($key, $value, $groupId, $sortOrder, 'select');
    }

    public function addConfigurationTextArea($key, $value, $groupId, $sortOrder)
    {
        $this->addConfiguration($key, $value, $groupId, $sortOrder, 'textArea');
    }

    public function addConfigurationOrderStatus($key, $value, $groupId, $sortOrder)
    {
        $this->addConfiguration($key, $value, $groupId, $sortOrder, 'orderStatus', 'xtc_get_order_status_name');
    }

    public function addCofigurationDropDown($key, $value, $groupId, $sortOrder, $values)
    {
        $arrayAsString = "['" . implode("','", $values) .  "']";
        $setFunction = 'xtc_cfg_select_option(' . $arrayAsString . ',';
        $this->addConfiguration($key, $value, $groupId, $sortOrder, $setFunction);
    }

    public function addCofigurationDropDownByStaticFunction($key, $value, $groupId, $sortOrder, $staticCallFunctionName)
    {
        $setFunction = 'xtc_cfg_select_option(' . get_class($this) . '::' . $staticCallFunctionName . '(),';
        $this->addConfiguration($key, $value, $groupId, $sortOrder, $setFunction);
    }

    public function addConfiguration($key, $value, $groupId, $sortOrder, $setFunction = '', $useFunction = '')
    {
        $key = $this->getModulePrefix() . '_' . $key;

        if ($setFunction == 'select') {
            $setFunction = "xtc_cfg_select_option(array('true', 'false'),";
        } elseif ($setFunction == 'textArea') {
            $setFunction = "xtc_cfg_textarea(";
        } elseif ($setFunction == 'orderStatus') {
            $setFunction = "xtc_cfg_pull_down_order_statuses(";
        }

        $setFunction = str_replace("'", "\\'", $setFunction);

        xtc_db_query("INSERT INTO `" . TABLE_CONFIGURATION . "` (`configuration_key`, `configuration_value`, `configuration_group_id`, `sort_order`, `set_function`, `use_function`, `date_added`) VALUES ('$key', '$value', '$groupId', '$sortOrder', '$setFunction', '$useFunction', NOW())");
    }

    public function deleteConfiguration($key)
    {
        $key = $this->getModulePrefix() . '_' . $key;

        xtc_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = '$key'");
    }

    public function setAdminAccess($key)
    {
        xtc_db_query("ALTER TABLE `" . TABLE_ADMIN_ACCESS . "` ADD `$key` INT(1) NOT NULL DEFAULT 0");
        xtc_db_query("UPDATE `" . TABLE_ADMIN_ACCESS . "` SET `$key` = 1 WHERE `customers_id` = 1");
        xtc_db_query("UPDATE `" . TABLE_ADMIN_ACCESS . "` SET `$key` = 1 WHERE `customers_id`='groups'");
    }

    public function deleteAdminAccess($key)
    {
        xtc_db_query("ALTER TABLE " . TABLE_ADMIN_ACCESS . " DROP $key");
    }
}
