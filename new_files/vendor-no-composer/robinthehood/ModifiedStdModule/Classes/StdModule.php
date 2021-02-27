<?php

namespace RobinTheHood\ModifiedStdModule\Classes;

$rthModifiedStdModuleMessages = [];

class StdModule
{
    public const VERSION = '';
    public const UPDATE_ERROR = -1;
    public const UPDATE_NOTHING = -1;
    public const UPDATE_SUCCESS = 1;

    public const MESSAGE_ERROR = -1;
    public const MESSAGE_SUCCESS = 1;

    public $code;
    public $title;
    public $description;
    public $enabled;
    public $sort_order;

    public $modulePrefix;
    public $keys;

    private $tempVersion;
    private $actions = [];

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

    public function addMessage($message, $messageType = self::MESSAGE_ERROR)
    {
        global $rthModifiedStdModuleMessages;

        $hash = md5($message);

        if (!$rthModifiedStdModuleMessages) {
            echo '<br>';
        }

        if ($messageType == self::MESSAGE_ERROR) {
            $class = 'error_message';
        } elseif ($messageType == self::MESSAGE_SUCCESS) {
            $class = 'success_message';
        } else {
            $class = 'error_message';
        }

        if (!$rthModifiedStdModuleMessages[$hash]) {
            echo '<div class="' . $class . '">' . $message . '</div>';
            $rthModifiedStdModuleMessages[$hash][$hash] = $message;
        }
    }

    public function addKey($key)
    {
        $this->keys[] = $this->getModulePrefix() . '_' . $key;
    }

    public function getTitle()
    {
        $version = $this->getVersion();
        $title = $this->getConfig('TITLE');
        if ($version) {
            return $title . ' (' . $version . ')';
        }
        return $title;
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

    public function getVersion()
    {
        if (!$this->tempVersion) {
            // Speichere Version in tempVersion, da Änderungen an der Datenbank Konstante
            // VERSION erst bei einem reload aktiv werden. setVersion speicher ebenfalls
            // einen neuen Wert in tempVersion.
            $this->tempVersion = $this->getConfig('VERSION');
        }
        return $this->tempVersion;
    }

    public function setVersion($version)
    {
        $this->tempVersion = $version;
        $this->deleteConfiguration('VERSION', $version);
        $this->addConfiguration('VERSION', $version, 6, 1);
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

        $installedVersion = $this->getVersion();
    
        if (static::VERSION && !$installedVersion) {
            $this->setVersion(static::VERSION);
        }
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

    public function renameConfiguration($oldKey, $newKey)
    {
        $oldKey = $this->getModulePrefix() . '_' . $oldKey;
        $newKey = $this->getModulePrefix() . '_' . $newKey;

        xtc_db_query("UPDATE `" . TABLE_CONFIGURATION . "` SET `configuration_key` = '$newKey' WHERE `configuration_key` = '$oldKey'");
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

    public function checkForUpdate()
    {
        if ($this->getVersion()) {
            $from = ' von ' . $this->getVersion();
        }

        if (static::VERSION && static::VERSION != $this->getVersion()) {
            $this->addMessage(
                $this->getConfig('TITLE') .
                ' benötigt ein Update ' . $from . ' auf ' .
                static::VERSION . ' - Klicken Sie dafür beim Modul auf Update.'
            );
        }
    }

    public function invokeUpdate()
    {
        $status = '';
        while ($status != self::UPDATE_NOTHING) {
            $versionBefore = $this->getVersion();
            $status = $this->updateSteps();
            $versionAfter = $this->getVersion();

            if ($versionBefore == $versionAfter) {
                break;
            }
        }

        $this->title = $this->getTitle();

        $this->addMessage(
            'Update von ' . $this->getConfig('TITLE') .
            ' auf Version ' . $this->getVersion() . ' erfolgreich.',
            self::MESSAGE_SUCCESS
        );
    }

    protected function updateSteps()
    {
        return self::UPDATE_NOTHING;
    }

    public function addAction($functionName, $buttonName = '')
    {
        $buttonName = $buttonName ?? $functionName;

        $this->actions[] = [
            'functionName' => $functionName,
            'buttonName' => $buttonName
        ];

        $buttons = '';
        foreach ($this->actions as $action) {
            $buttons .= $this->renderButton($action['functionName'], $action['buttonName']);
        }

        $this->description = $buttons . $this->getDescription();

        if ($_GET['moduleaction'] == $functionName) {
            $functionName = 'invoke' . ucfirst($functionName);
            $this->$functionName();
        }
    }

    private function renderButton($functionName, $buttonName)
    {
        $url = xtc_href_link(
            FILENAME_MODULE_EXPORT,
            'set=system&module=' . $this->code . '&moduleaction=' . $functionName
        );

        return '
            <a class="button btnbox" style="text-align:center;" onclick="this.blur();" href="' . $url . '">' . $buttonName . '</a>
        ';
    }
}
