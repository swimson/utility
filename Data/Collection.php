<?php namespace Swimson\Utility\Data;

class Collection {
    
     /* constants */
    const C_ARRAY = 1;
    const C_OBJECT = 2;
    const C_BOOLEAN = 3;
    const C_INTEGER = 4;
    const C_FLOAT = 5;
    const C_STRING = 6;
    const C_RESOURCE = 7;
    const C_NULL = 8;
    const C_CLOSURE = 9;
    
    /* errors and exceptions */
    const E_UKNOWN_TYPE = "Unknown variable type";
    const E_VALIDATION = "Validation for element failed";
    const E_SEARCH_ARG = "Invalid argument type for search argument.";
    const E_OVERWRITE = "Unable to overwrite element when trying to SET.";
    const E_DOES_NOT_EXIST = "Unable to retrieve element that hasn't been SET.";
    
    
    /* private properties */
    private $collection = array();
    private $validator;
    private $errorHandler;
    private $formatFunction;

    // options
    private $optionMuteAllErrors = false;
    private $optionPreventOverwrites = false;
    private $optionConfirmExists = false;
    private $optionAlertInvalid = false;
    
    // iterator properties
    private $iteratorPosition = 0;
    
    
    
    /* constructor */
    public function __construct($loadData = array(), \Closure $validator = null,  \Closure $formatFunction=null, \Closure $errorHandler=null, array $options = array())
    {
        $this->iteratorPosition = 0;
        $this->config($options);   
        
        // set error handler if available
        if($errorHandler){
            $this->setErrorHandler($errorHandler);
        }
        
        // set validator if available
        if ($validator) {
            $this->setValidator($validator);
        } 
        
        // set format function if available
        if ($formatFunction) {
            $this->setFormatter($formatFunction);
        } 
        
        // load data if available
        if ($loadData) {
            $this->append($loadData);
        }
    }
    
    public function config(array $options)
    {
        // thow exceptions
        if(!empty($options['muteErrors'])){
            $this->optionMuteAllErrors = $options['muteErrors'];
        }
        
        // prevent overwrites
        if(!empty($options['preventOverwrites'])){
            $this->optionPreventOverwrites = $options['preventOverwrites'];
        }
        
        // confirm exists
        if(!empty($options['confirmExists'])){
            $this->optionConfirmExists = $options['confirmExists'];
        }
        
        // alert invalid
        if(!empty($options['alertInvalid'])){
            $this->optionAlertInvalid = $options['alertInvalid'];
        }
        return $this;
    }
    
    // returns a new collection satisfying the query
    public function pluck($qry)
    {
        $that = $this->copy();
        $that->filter($qry);
        return $that;
    }
    
    /* adds an array of elements to the collection */
    public function append(array $loadData = array())
    {
        foreach ($loadData as $key => $elmt) {
            $this->set($key,$elmt);
        }
        return $this;
    }
    
    /* add a single element */
    public function set($key, $elmt)
    {
        // optional exception
        if($this->optionPreventOverwrites && $this->exist($key)){
            $this->error(new \Exception(self::E_OVERWRITE));
        }
        
        if($this->validate($elmt)){
            $this->collection[$key] = $elmt;
        }
        return $this;
    }
    
    /* get a single element */
    public function get($key)
    {
        // optional exception
        if($this->optionConfirmExists && !$this->exist($key)){
            $this->error(new \Exception(self::E_DOES_NOT_EXIST));
        }
        
        $return = null;
        if ($this->exist($key)) {
            $return = $this->collection[$key];
        } 
        
        $return = $this->format($return);
        
        return $return;
    }
    
    /* returns an array of collection keys */
    public function keys()
    {
        return array_keys($this->collection);
    }
    
    /* reduces collection down to elements satisfying the filter */
    public function filter($qry=null)
    {
        $filter = array();
        $selection = $this->search($qry);
        foreach ($selection as $key) {
            $filter[$key] = $this->collection[$key];
        }
        $this->collection = $filter;
        return $this;
    }
    
    /* returns true if key exists */
    public function exist($key)
    {
        $return = false;
        if(array_key_exists($key, $this->collection)){
            $return = true;
        }
        return $return;
    }
    
    /* removes selection from collection */
    public function remove($qry)
    {
        $selection = $this->search($qry);
        foreach ($selection as $key) {
            unset($this->collection[$key]);
        }
        return $this;
    }
    
    /* removes all elements from the collection */
    public function clear()
    {   
        $this->collection  = array();
        return $this;
    }
    
    /* returns the number of elements in the collection */
    public function count()
    {
        return count($this->collection);
    }
    
    /* returns an array with the collection elements */
    public function toArray($associativeArray=true)
    {
        $return = null;
        if($associativeArray){
            $return = $this->collection;
        } else {
            foreach($this->collection as $elmt){
                $return[] = $elmt;
            }
        }
        return $return;
        
    }
    
    /* applys a function to each element in the collection */
    public function map(\Closure $c)
    {
        foreach ($this->collection as $key => $elmt) {
            $this->collection[$key] = $c($elmt);
        }
        return $this;
    }
    
    /* applies a function to each consecutive element and combines into single value */
    public function reduce(\Closure $c, $initialValue)
    {
        $val = $initialValue;
        foreach($this->collection as $elmt){
            $val = $c($val, $elmt);
        }
        $val = $this->format($val);
        
        return $val;
    }
    
    /* returns a clone of the collection */
    public function copy(){
        $that = clone($this);
        return $that;
    }
    
    /* debugger */
    public function debug($echo = true, $break = "\r\n")
    {
        $typeStr = null;
        $return = $break.$break."Collection: ".$break;
        foreach($this->collection as $key=>$elmt){
            $type = $this->detType($elmt);
            $val = null;
            switch($type){
                case $this::C_BOOLEAN:
                    $typeStr = "Boolean";
                    if ($elmt) {
                        $val = "True";
                    } else {
                        $val = "False";
                    }
                    break;
                case $this::C_INTEGER:
                    $typeStr = "Integer";
                    $val = $elmt;
                    break;
                case $this::C_STRING:
                    $typeStr = "String";
                    $val = (strlen($elmt)>50) ? substr($elmt, 0, 50)."..." : $elmt;
                    break;
                case $this::C_FLOAT:
                    $typeStr = "Float";
                    $val = $elmt;
                    break;
                case $this::C_ARRAY:
                    $typeStr = "Array";
                    $val = "Size = ".count($elmt);
                    break;
                case $this::C_NULL:
                    $typeStr = "Null";
                    $val = "Null";
                    break;
                case $this::C_OBJECT:
                    $typeStr = "Object";
                    $val = get_class($elmt);
                    break;
                case $this::C_RESOURCE:
                    $typeStr = "Resource";
                    $val = "Resource Type -".get_resource_type($elmt);
                    break;
                case $this::C_CLOSURE:
                    $typeStr = "Closure";
                    break;
            }
            $return = $return."$key : [".$typeStr."]  $val $break";
        }
        if ($echo) {
            echo $return;

            return null;
        } else {
            return $return;
        }
    }
    
    
    // callback methods
    
    /* validator applied to each element */
    public function setValidator(\Closure $validator, $applyToCurrent = true)
    {
        $collection = array();
            
        // apply validator to each element in collection
        if($applyToCurrent==true){
            foreach ($this->collection as $key=>$elmt) {
                if ($validator($elmt) == true) {
                    $collection[$key] = $elmt;
                }
            }
        }
        
        $this->collection = $collection;
        $this->validator = $validator;
        return $this;  
    }
    
    /* error handler */
    public function setErrorHandler(\Closure $errorHandler)
    {
        $this->errorHandler = $errorHandler;
        return $this;
    }
    
    /* format function */
    public function setFormatter(\Closure $formatFunction)
    {
        $this->formatFunction = $formatFunction;
        return $this;
    }
    
    
    /* public helper methods */
    
    /* format an element */
    public function format($elmt)
    {
        $return = $elmt;
        if ($this->formatFunction) {
            $f = $this->formatFunction;
            $return = $f($return);
        }
        return $return;
    }
    
    /* validate an element */
    public function validate($elmt)
    {
        if($this->validator){
            try {
                // check if entry is valid
                $validator = $this->validator;
                if ($validator($elmt) == true){
                    $return = true;
                } else {
                    if ($this->optionAlertInvalid) {
                        $this->error(new \Exception(self::E_VALIDATION));
                    }
                    $return = false;
                }
                
            } catch (\Exception $e) {
                // route errors in validator to error handler
                $this->error($e);
                $return = false;
            }
        } else {
             $return = true;
        }
        return $return;
    }
    
    /* public helper methods */
    
    /* determines the type of the element */
    private function detType($thing)
    {
        $return = null;
        if (is_object($thing)) {
            if(get_class($thing)=='Closure'){
                $return = self::C_CLOSURE;
            } else {
                $return = self::C_OBJECT;
            }
        } elseif(is_array($thing)) {
            $return = self::C_ARRAY;
        } elseif(is_bool($thing)) {
            $return = self::C_BOOLEAN;
        } elseif(is_int($thing)) {
            $return = self::C_INTEGER;
        } elseif(is_float($thing)) {
            $return = self::C_FLOAT;
        } elseif(is_string($thing)) {
            $return = self::C_STRING;
        } elseif(is_null($thing)) {
            $return = self::C_NULL;
        } elseif(is_resource($thing)) {
            $return = self::C_RESOURCE;
        } else {
            $this->error(new \Exception(self::E_UKNOWN_TYPE));
        }
        return $return;
    }
    
    
    // $qry can be string and will return $array
    // ... or $qry can be an array of element keys
    // ... or $qry can be a callable that is applied to each element to generate a selection set
    
    private function search($qry)
    {
        
        $selection = array();
        $qryType = $this->detType($qry);
        
        if ($qryType == self::C_STRING) {
            $key = $qry;
            if ($this->exist($key)) {
                $selection[] = $key;
            }
        } elseif ($qryType == self::C_ARRAY) {
            foreach ($qry as $key) {
                if ($this->exist($key)) {
                    $selection[] = $key;
                }
            }
        } elseif ($qryType == self::C_CLOSURE) {
            foreach ($this->collection as $key =>$elmt) {
                if($qry($elmt)==true){
                    $selection[] = $key;
                }
            }
        } else {
            $this->error(new \Exception(self::E_SEARCH_ARG));
        }
         
        return $selection;
    }
    
    /* handles internal errors */
    private function error(\Exception $e)
    {
        if(!$this->optionMuteAllErrors){
            if ($this->errorHandler) {
                $errorHandler = $this->errorHandler;
                $errorHandler($e);
            } else {
                throw $e;
            }
        }
    }
}