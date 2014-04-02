<?php
namespace Swimson\Utility\FluentArray;

interface ArrayAdapterInterface
{
    /**
     * Converts the internal representation to an array
     * @return array
     */
    public function toArray();

}