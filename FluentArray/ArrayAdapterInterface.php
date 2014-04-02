<?php
namespace Swimson\Utility\FluentObject;

interface ArrayAdapterInterface
{
    /**
     * Converts the internal representation to an array
     * @return array
     */
    public function toArray();

}