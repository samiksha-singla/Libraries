<?php
namespace libDb\Table;
class Definition
{

    /**
     * @var array
     */
    protected $_tableConfigs = array();

    /**
     * __construct()
     *
     * @param array|\libDb\ConfigLoader $options
     */
    public function __construct($options = null)
    {
        if ($options instanceof \libDb\ConfigLoader) {
            $this->setConfig($options);
        } elseif (is_array($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * setConfig()
     *
     * @param \libDb\ConfigLoader $config
     * @return Definition
     */
    public function setConfig(\libDb\ConfigLoader $config)
    {
        $this->setOptions($config->toArray());
        return $this;
    }

    /**
     * setOptions()
     *
     * @param array $options
     * @return Definition
     */
    public function setOptions(Array $options)
    {
        foreach ($options as $optionName => $optionValue) {
            $this->setTableConfig($optionName, $optionValue);
        }
        return $this;
    }

    /**
     * @param string $tableName
     * @param array  $tableConfig
     * @return Definition
     */
    public function setTableConfig($tableName, array $tableConfig)
    {
        // @todo logic here
        $tableConfig[\libDb\Table::DEFINITION_CONFIG_NAME] = $tableName;
        $tableConfig[\libDb\Table::DEFINITION] = $this;

        if (!isset($tableConfig[\libDb\Table::NAME])) {
            $tableConfig[\libDb\Table::NAME] = $tableName;
        }

        $this->_tableConfigs[$tableName] = $tableConfig;
        return $this;
    }

    /**
     * getTableConfig()
     *
     * @param string $tableName
     * @return array
     */
    public function getTableConfig($tableName)
    {
        return $this->_tableConfigs[$tableName];
    }

    /**
     * removeTableConfig()
     *
     * @param string $tableName
     */
    public function removeTableConfig($tableName)
    {
        unset($this->_tableConfigs[$tableName]);
    }

    /**
     * hasTableConfig()
     *
     * @param string $tableName
     * @return bool
     */
    public function hasTableConfig($tableName)
    {
        return (isset($this->_tableConfigs[$tableName]));
    }

}
