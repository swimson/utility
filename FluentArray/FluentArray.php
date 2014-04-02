<?php
namespace Swimson\Utility\FluentArray;

class FluentArray implements ArrayAdapterInterface
{

    private $nodes = array();

    public function __construct($nodes = array())
    {
        if ($nodes instanceof ArrayAdapterInterface) {
            $nodes = $nodes->toArray();
        }
        if (is_array($nodes)) {
            foreach ($nodes as $name => $value) {
                $this->createNode($name, $value);
            }
        }
    }

    public function __get($name)
    {
        if (!array_key_exists($name, $this->nodes)) {
            $this->createNode($name, array());
        }

        return $this->nodes[$name];
    }

    public function __set($name, $value)
    {
        if (is_null($value)) {
            $this->deleteNode($name);
        } else {
            $this->createNode($name, $value);
        }
    }

    private function deleteNode($name)
    {
        if (isset($this->nodes[$name])) {
            unset($this->nodes[$name]);
        }
    }

    private function createNode($name, $value)
    {
        if (is_array($value)) {
            $this->nodes[$name] = $this->createComplexNode($value);
        } elseif (is_object($value)) {
            if ($value instanceof ArrayAdapterInterface) {
                $this->nodes[$name] = $this->createComplexNode($value->toArray());
            } else {
                $this->nodes[$name] = $this->createSimpleNode($value);
            }
        } else {
            $this->nodes[$name] = $this->createSimpleNode($value);
        }
    }

    private function createComplexNode($value = array())
    {
        return new FluentArray($value);
    }

    private function createSimpleNode($value)
    {
        return $value;
    }

    public function toArray()
    {
        $return = array();
        foreach ($this->nodes as $name => $value) {
            if (get_class($value) == get_class($this)) {
                $return[$name] = $value->toArray();
            } else {
                $return[$name] = $value;
            }
        }

        return $return;
    }
}