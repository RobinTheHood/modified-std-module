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
    public $keys = [];

    private $tempVersion;
    private $actions = [];

    public static function isEnabled(string $module)
    {
        $statusConstant = $module . '_STATUS';

        if (defined($statusConstant) && 'true' === constant($statusConstant)) {
            return true;
        }

        return false;
    }

    public static function isDisabled(string $module)
    {
        $isDisabled = !self::isEnabled($module);

        return $isDisabled;
    }

    public function __construct($modulePrefix = '', $code = '')
    {
        $class = get_class($this);

        if ($modulePrefix) {
            $this->modulePrefix = $modulePrefix;
        } else {
            $this->modulePrefix = 'MODULE_' . strtoupper($class);
        }

        if ($code) {
            $this->code = $code;
        } else {
            $this->code = $class;
        }

        $this->title = $this->getTitle();
        $this->description = $this->getDescription();
        $this->sort_order = $this->getSortOrder();
        $this->enabled = $this->getEnabled();

        $this->addKey('STATUS');
    }

    public function init($modulePrefix, $code = '')
    {
        self::__construct($modulePrefix, $code);

        /** E_USER_DEPRECATED does not work */
        trigger_error('Using the init method is deprecated. Use parent::__construct instead.', E_USER_NOTICE);
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
        $fullKeyName = $this->getModulePrefix() . '_' . $key;
        if (in_array($fullKeyName, $this->keys())) {
            return;
        }

        $this->keys[] = $fullKeyName;
    }

    public function getTitle()
    {
        $version = $this->getVersion();
        $title = $this->getConfig('TITLE');
        if ($version) {
            return $title . ' (v' . $version . ')';
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

    public function getConfig($name, $default = false): string
    {
        $constantName = $this->getModulePrefix() . '_' . $name;
        $configurationValue = defined($constantName) ? constant($constantName) : $default;

        return $configurationValue;
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

        if ($this->getVersion()) {
            $this->deleteConfiguration('VERSION');
        }
    }

    public function keys()
    {
        return $this->keys;
    }

    public function addConfigurationSelect($key, $value, $groupId, $sortOrder)
    {
        $setFunction = [
            'name' => 'xtc_cfg_select_option',
            'params' => "array('true', 'false')"
        ];
        $this->addConfiguration($key, $value, $groupId, $sortOrder, $setFunction);
    }

    public function addConfigurationTextArea($key, $value, $groupId, $sortOrder)
    {
        $setFunction = 'xtc_cfg_textarea';
        $this->addConfiguration($key, $value, $groupId, $sortOrder, $setFunction);
    }

    public function addConfigurationOrderStatus($key, $value, $groupId, $sortOrder)
    {
        $setFunction = 'xtc_cfg_pull_down_order_statuses';
        $useFunction = 'xtc_get_order_status_name';
        $this->addConfiguration($key, $value, $groupId, $sortOrder, $setFunction, $useFunction);
    }

    public function addConfigurationDropDown($key, $value, $groupId, $sortOrder, $values)
    {
        // $arrayAsString = "['" . implode("','", $values) .  "']";
        // $setFunction = 'xtc_cfg_select_option(' . $arrayAsString . ',';
        $setFunction = [
            'name' => 'xtc_cfg_select_option',
            'params' => "['" . implode("','", $values) .  "']"
        ];

        $this->addConfiguration($key, $value, $groupId, $sortOrder, $setFunction);
    }

    public function addConfigurationDropDownByStaticFunction($key, $value, $groupId, $sortOrder, $staticCallFunctionName)
    {
        // $setFunction = 'xtc_cfg_select_option(' . get_class($this) . '::' . $staticCallFunctionName . '(),';
        $callFunction = get_class($this) . '::' . $staticCallFunctionName;
        $setFunction = [
            'name' => 'xtc_cfg_select_option',
            'params' => $callFunction . '()';
        ];
        $this->addConfiguration($key, $value, $groupId, $sortOrder, $setFunction);
    }

    public function addConfiguration($key, $value, $groupId, $sortOrder, $setFunction = '', $useFunction = '')
    {
        $key = $this->getModulePrefix() . '_' . $key;

        // TODO: remove this if and else block. Or mark as depricated - start
        if ($setFunction == 'select') {
            $setFunction = [
                'name' => 'xtc_cfg_select_option',
                'params' => "array('true', 'false')"
            ];
        } elseif ($setFunction == 'textArea') {
            $setFunction = "xtc_cfg_textarea";
        } elseif ($setFunction == 'orderStatus') {
            $setFunction = "xtc_cfg_pull_down_order_statuses";
        }
        // TODO: remove this if and else block. Or mark as depricated - end

        $setFunction = $this->buildFunctionString($setFunction);

        // TODO: create something like a new private dbInsertArray() method
        xtc_db_query("INSERT INTO `" . TABLE_CONFIGURATION . "` (`configuration_key`, `configuration_value`, `configuration_group_id`, `sort_order`, `set_function`, `use_function`, `date_added`) VALUES ('$key', '$value', '$groupId', '$sortOrder', '$setFunction', '$useFunction', NOW())");
    }

    private function buildFunctionString($function): string
    {
        if (!$function) {
            return '';
        }

        if (is_array($function)) {
            if (array_key_exists('params', $function)) {
                if (is_array($function['params'])) {
                    $params = implode(', ', $function['params']);
                } else {
                    $params = $function['params'];
                }
                $functionString = $function['name'] . '(' . $params . ',';
            } else {
                $functionString = $function['name'];
            }
        }

        $lastChar = substr($functionString, -1);
        if ($lastChar !== '(' &&  $lastChar !== ',') {
            $functionString .= '(';
        }

        $functionString = str_replace("'", "\\'", $functionString);
        return $functionString;
    }

    public function removeConfiguration(string $key): bool
    {
        $key = $this->getModulePrefix() . '_' . $key;
        $remove_key_query = xtc_db_query(
            sprintf(
                /** TRANSLATORS: %1$s: Database table "configuration". %2$s: Value for "configuration_key". */
                'DELETE FROM `%1$s` WHERE `configuration_key` = "%2$s"',
                TABLE_CONFIGURATION,
                $key
            )
        );

        $success = false !== $remove_key_query;

        return $success;
    }

    public function deleteConfiguration(string $key): bool
    {
        /** E_USER_DEPRECATED does not work */
        trigger_error('Using the deleteConfiguration method is deprecated. Use removeConfiguration instead.', E_USER_NOTICE);

        return $this->removeConfiguration($key);
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

        /** Set access for admin who doesn't have an ID of 1 */
        if (isset($_SESSION['customer_id']) && '1' !== $_SESSION['customer_id']) {
            $accessExistsQuery = xtc_db_query("SELECT * FROM " . TABLE_ADMIN_ACCESS . ' WHERE `customers_id` = ' . $_SESSION['customer_id']);

            if (xtc_db_num_rows($accessExistsQuery) >= 1) {
                xtc_db_query("UPDATE `" . TABLE_ADMIN_ACCESS . "` SET `$key` = 1 WHERE `customers_id` = " . $_SESSION['customer_id'] );
            }
        }
    }

    public function deleteAdminAccess($key)
    {
        xtc_db_query("ALTER TABLE " . TABLE_ADMIN_ACCESS . " DROP $key");
    }

    /**
     * Checks for module updates. Returns whether an update is available.
     *
     * @param bool $showUpdateButton Whether to show the Update button.
     *
     * @return bool
     */
    public function checkForUpdate($showUpdateButton = false): bool
    {
        /** Abort if the user is not an admin */
        if (!$this->isAdmin()) {
            return false;
        }

        if (!$this->enabled) {
            return false; // do not check for update
        }

        if (!static::VERSION) {
            return false; // do not check for update
        }

        $this->invokeAction();

        $action = $_GET['action'] ?? '';
        if ($action) {
            return false; // do not check for update
        }

        $moduleAction = $_GET['moduleaction'] ?? '';
        if ($moduleAction) {
            return false; // do not check for update
        }

        if (-1 !== version_compare($this->getVersion(), static::VERSION)) {
            return false; // module is up-to-date
        }

        if ($this->getVersion()) {
            $from = ' von ' . $this->getVersion();
        }

        // TODO: extract to own private method
        if (isset($_SERVER['SCRIPT_NAME'], $_GET['set'])) {
            $moduleLink = xtc_href_link(
                pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_BASENAME),
                http_build_query(
                    [
                        'set' => $_GET['set'],
                        'module' => $this->code
                    ]
                ),
                'SSL'
            );

            $moduleName = '<a href="' . $moduleLink . '">' . $this->getConfig('TITLE') . '</a>';
        } else {
            $moduleName = $this->getConfig('TITLE');
        }

        $this->addMessage(
            sprintf(
                /** TRANSLATORS: %1$s: Module name. %2$s: Module current version. %3$s: Module new version. */
                '%1$s benötigt ein Update von %2$s auf %3$s - Klicken Sie dafür beim Modul auf Update.',
                $moduleName,
                $from,
                static::VERSION
            )
        );

        if ($showUpdateButton) {
            $this->addAction('update', 'Update');
        }

        return true;
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
        if (!$this->enabled) {
            return;
        }

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

        $this->invokeAction();
    }

    private function invokeAction()
    {
        $module = $_GET['module'] ?? '';
        if ($module != $this->code) {
            return;
        }

        $functionName = $_GET['moduleaction'] ?? '';
        $functionName = 'invoke' . ucfirst($functionName);

        if (!method_exists($this, $functionName)) {
            return;
        }

        $this->$functionName();
    }

    private function renderButton($functionName, $buttonName)
    {
        if (!isset($_SERVER['SCRIPT_NAME'], $_GET['set'])) {
            return '';
        }

        $url = xtc_href_link(
            pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_BASENAME),
            http_build_query(
                [
                    'set' => $_GET['set'],
                    'module' => $this->code,
                    'moduleaction' => $functionName
                ]
            )
        );

        return '
            <a class="button btnbox" style="text-align:center;" onclick="this.blur();" href="' . $url . '">' . $buttonName . '</a>
        ';
    }

    private function isAdmin(): bool
    {
        $customerStatusId = $_SESSION['customers_status']['customers_status_id'] ?? '';
        if ($customerStatusId !== '0') {
            return false;
        }
        return true;
    }
}
