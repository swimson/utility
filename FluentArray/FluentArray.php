<?php

namespace Swimson\Utility\ObjectForge;

use ArrayAccess;
use Iterator;

class FluentArray extends FluentObject implements ArrayAccess, Iterator
{

    public function offsetExists($key)
    {
        return $this->nodeExists($key);
    }

    public function offsetUnset($key)
    {
        $this->deleteNode($key);
    }

    public function offsetGet($key)
    {
        return $this->__get($key);
    }

    public function offsetSet($key, $value)
    {
        $this->__set($key, $value);
    }

    public function current()
    {
        return $this->nodeCurrent();
    }

    public function key()
    {
        return $this->nodeKey();
    }

    public function next()
    {
        $this->nodeNext();
    }

    public function rewind()
    {
        $this->nodeRewind();
    }

    public function valid()
    {
        return $this->offsetExists($this->key());
    }


} 