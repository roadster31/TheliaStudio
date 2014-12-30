<?php

namespace TheliaStudio\Parser;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class Table
 * @package TheliaStudio\Parser
 * @author Benjamin Perche <bperche9@gmail.com>
 */
class Table
{
    /** @var  string */
    protected $tableName;

    /** @var Column[] */
    protected $columns = array();

    /** @var array  */
    protected $behaviors;

    protected $namespace;

    public function __construct($tableName, $namespace = '', array $behaviors = array())
    {
        $this->tableName = $tableName;
        $this->behaviors = $behaviors;
        $this->namespace = $namespace;
    }

    public function addColumn(Column $column)
    {
        $this->columns[$column->getName()] = $column;
    }

    public function getColumn($name, $default = null)
    {
        return $this->has($name) ? $this->columns[$name] : $default;
    }

    public function has($name)
    {
        return isset($this->columns[$name]);
    }

    public function hasPosition()
    {
        return $this->has("position");
    }

    public function hasVisible()
    {
        return $this->has("visible");
    }

    public function addBehavior($name)
    {
        if (!in_array($name, $this->behaviors)) {
            $this->behaviors[] = $name;
        }

        return $this;
    }

    public function hasBehavior($name)
    {
        return in_array($name, $this->behaviors);
    }

    public function hasI18nBehavior()
    {
        return $this->hasBehavior("i18n");
    }

    public function hasTimestampableBehavior()
    {
        return $this->hasBehavior("timestampable");
    }

    public function getQueryClass()
    {
        return $this->getModelClass() . "Query";
    }

    public function getFullQueryClass()
    {
        return $this->getFullModelClass() . "Query";
    }

    public function getModelClass()
    {
        return Container::camelize($this->tableName);
    }

    public function getFullModelClass()
    {
        return  $this->getNamespace() . "\\" . $this->getModelClass();
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return Container::camelize($this->tableName);
    }

    public function getRawTableName()
    {
        return $this->tableName;
    }

    /**
     * @return Column[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return array
     */
    public function getBehaviors()
    {
        return $this->behaviors;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    public function getI18nColumns()
    {
        $collection = array();

        foreach ($this->columns as $column) {
            if ($column->isI18n()) {
                $collection[] = $column;
            }
        }

        return $collection;
    }
}