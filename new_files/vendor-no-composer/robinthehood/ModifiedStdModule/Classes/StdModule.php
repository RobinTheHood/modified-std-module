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

    public function addConfiguration($key, $value, $groupId, $sortOrder, $function = '')
    {
        $key = $this->getModulePrefix() . '_' . $key;

        if ($function == 'select') {
            $function = "xtc_cfg_select_option(array('true', 'false'),";
        } elseif ($function == 'textArea') {
            $function = "xtc_cfg_textarea(";
        }

        $function = str_replace("'", "\\'", $function);

        xtc_db_query("INSERT INTO `" . TABLE_CONFIGURATION . "` (`configuration_key`, `configuration_value`, `configuration_group_id`, `sort_order`, `set_function`, `date_added`) VALUES ('$key', '$value', '$groupId', '$sortOrder', '$function', now())");
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
