<?php
namespace libDb\Table;

use libDb\Table\AbstractTable;

class Select extends \libDb\Select
{
    /**
     * Table schema for parent \libDb\Table.
     *
     * @var array
     */
    protected $_info;

    /**
     * Table integrity override.
     *
     * @var array
     */
    protected $_integrityCheck = true;

    /**
     * Table instance that created this select object
     *
     * @var AbstractTable
     */
    protected $_table;

    /**
     * Class constructor
     *
     * @param AbstractTable $adapter
     */
    public function __construct(AbstractTable $table)
    {
        parent::__construct($table->getAdapter());
        $this->setTable($table);
    }

    /**
     * Return the table that created this select object
     *
     * @return AbstractTable
     */
    public function getTable()
    {
        return $this->_table;
    }

    /**
     * Sets the primary table name and retrieves the table schema.
     *
     * @param AbstractTable $adapter
     * @return \libDb\Select This \libDb\Select object.
     */
    public function setTable(AbstractTable $table)
    {
        $this->_adapter = $table->getAdapter();
        $this->_info    = $table->info();
        $this->_table   = $table;
                
        return $this;
    }

    /**
     * Sets the integrity check flag.
     *
     * Setting this flag to false skips the checks for table joins, allowing
     * 'hybrid' table rows to be created.
     *
     * @param AbstractTable $adapter
     * @return \libDb\Select This \libDb\Select object.
     */
    public function setIntegrityCheck($flag = true)
    {
        $this->_integrityCheck = $flag;
        return $this;
    }

    /**
     * Tests query to determine if expressions or aliases columns exist.
     *
     * @return boolean
     */
    public function isReadOnly()
    {
        $readOnly = false;
        $fields   = $this->getPart(\libDb\Select::COLUMNS);
        $cols     = $this->_info[AbstractTable::COLS];

        if (!count($fields)) {
            return $readOnly;
        }

        foreach ($fields as $columnEntry) {
            $column = $columnEntry[1];
            $alias = $columnEntry[2];

            if ($alias !== null) {
                $column = $alias;
            }

            switch (true) {
                case ($column == self::SQL_WILDCARD):
                    break;

                case ($column instanceof \libDb\Expr):
                case (!in_array($column, $cols)):
                    $readOnly = true;
                    break 2;
            }
        }

        return $readOnly;
    }

    /**
     * Adds a FROM table and optional columns to the query.
     *
     * The table name can be expressed
     *
     * @param  array|string|\libDb\Expr|AbstractTable $name The table name or an
                                                                      associative array relating
                                                                      table name to correlation
                                                                      name.
     * @param  array|string|\libDb\Expr $cols The columns to select from this table.
     * @param  string $schema The schema name to specify, if any.
     * @return Select This  object.
     */
    public function from($name, $cols = self::SQL_WILDCARD, $schema = null)
    {
        if ($name instanceof AbstractTable) {
            $info = $name->info();
            $name = $info[AbstractTable::NAME];
            if (isset($info[AbstractTable::SCHEMA])) {
                $schema = $info[AbstractTable::SCHEMA];
            }
        }

        return $this->joinInner($name, null, $cols, $schema);
    }

    /**
     * Performs a validation on the select query before passing back to the parent class.
     * Ensures that only columns from the primary \libDb\Table are returned in the result.
     *
     * @return string|null This object as a SELECT string (or null if a string cannot be produced)
     */
    public function assemble()
    {
        $fields  = $this->getPart(\libDb\Table\Select::COLUMNS);
        $primary = $this->_info[AbstractTable::NAME];
        $schema  = $this->_info[AbstractTable::SCHEMA];      
        
        if (count($this->_parts[self::UNION]) == 0) {

            // If no fields are specified we assume all fields from primary table
            if (!count($fields)) {
                $this->from($primary, self::SQL_WILDCARD, $schema);
                $fields = $this->getPart(\libDb\Table\Select::COLUMNS);
            }
            $from = $this->getPart(\libDb\Table\Select::FROM);

            if ($this->_integrityCheck !== false) {
                foreach ($fields as $columnEntry) {
                    list($table, $column) = $columnEntry;

                    // Check each column to ensure it only references the primary table
                    if ($column) {
                        if (!isset($from[$table]) || $from[$table]['tableName'] != $primary) {
                            throw new \libDb\Table\Select\Exception('Select query cannot join with another table');
                        }
                    }
                }
            }
        }

        return parent::assemble();
    }
}
