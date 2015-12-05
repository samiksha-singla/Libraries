<?php
namespace libDb;
use \libDb\Table\Definition;

class Table extends \libDb\Table\AbstractTable
{

    /**
     * __construct() - For concrete implementation of Table
     *
     * @param string|array $config string can reference a \libDb\Registry key for a db adapter
     *                             OR it can reference the name of a table
     * @param array|Definition $definition
     */
    public function __construct($config = array(), $definition = null)
    {       
        if ($definition !== null && is_array($definition)) {
            $definition = new Definition($definition);
        }

        if (is_string($config)) {
            if (\libDb\Registry::isRegistered($config)) {
                trigger_error(__CLASS__ . '::' . __METHOD__ . '(\'registryName\') is not valid usage of \libDb\Table, '
                    . 'try extending \libDb\AbstractTable in your extending classes.',
                    E_USER_NOTICE
                    );
                $config = array(self::ADAPTER => $config);
            } else {
                // process this as table with or without a definition
                if ($definition instanceof Definition
                    && $definition->hasTableConfig($config)) {
                    // this will have DEFINITION_CONFIG_NAME & DEFINITION
                    $config = $definition->getTableConfig($config);
                } else {
                    $config = array(self::NAME => $config);
                }
            }
        }       
        parent::__construct($config);
    }
}
