                          Swimson/Collection


What is it?
-----------

The Collection is a simple object that extends your native PHP array.  It provides a thin layer of   
functionality and can be used as a drop-in-replacement for associative arrays.  Using a collection   
you have access to validation, filtering, formatting, map/reduce, and error handling.  It also gracefully   
degrades by returning a NULL instead of throwing an error when you try to access a key that hasn't been set.


Great Use Cases:
----------------

**(1) Replace your Ternary Operators** - Using a Collection you no longer need to check if an element exists  
because NULL is returned an element is missing.   

**(2) Be more explicit with functions** - PHP function values are assigned by position.    
Prevent confusion by accepting a collection object which provides documentation inside your code.   

    
**(3) Validate Elements** - You add a validator to your collection to   
ensure that your elements are the right type or have the right structure.  


**(4) Easy Formatting** - You can add a format function so that all of your  
elements look the same when they are retrieved from your collection.  


**(5) Map/Reduce** - You can easily apply a map/reduce operation   
on any collection.  $col->map(function($elmt))->reduce(function($elmt, $ini));


**(5) Filter/Pluck** - You can apply a filter on a collection to reduce   
to a smaller collection that pass the filter.    
   
   
   

The Latest Version
------------------

Details of the latest version can be found on the at the GitHub page    
http://github.com/swimson/Utility/Collection.  


Code Examples:
--------------

**(1) Loading/Removing Data** - You can store any of PHP variable types including string, integer,    
float, object, boolean, closures, array, and resource.

*Loading when you initialize the collection*

    $data = array(1, 2, 3, 4, 5);
    $c = new Collection($data); 

*Using the load method*  

    $data = array('a' => 'apple', 'b'=> 'bannana');
    $c = new Collection();
    $c->load($data);  

*Using the set method*

    $c = new Collection();
    $c->set('foo', 'Hello');
    $c->set('bar', 'World')
    echo $c->count();
    ==> 2

*Removing Data*   

    $c = new Collection();
    $c->set('foo', 'Hello');
    $c->set('bar', 'World')
    $c->remove('bar');
    echo $c->count();
    ==> 1

*Clearing All Data*   

    $c = new Collection();
    $c->set('foo', 'Hello');
    $c->set('bar', 'World')
    $c->clear();
    echo $c->count();
    ==> 0



**(2) Using a Validator** - By attaching a validator to your Collection you can   
ensure that all the elements are consistent.  To create a validator, just create an   
anonymous function that returns either true (valid) or false.    

    $data = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
    $isEven = function($elmt){  
    if($elmt % 2 == 0){  
      return true;  
    } else {  
      return false;
    }};
    $c = new Collection($data, $isEven); 

    ==> [2, 4, 6, 8, 10]

> **Note** By default, the validator will be applied to all current and future elements.  
> If you want to skip elements already added to your collection pass false along
> with the validator.  
> Example:  $collection->setValidator($function, false);

**(3) Formatting Output**  Attach a function that will be applied to each element  
when you retrieve an element from the collection.  Allows you to easily format elements.  

    $formatter = function($elmt){  
       $return = '$'.round($elmt,2);
       return $return;
    };
    $c = new Collection(); 
    $c->set('foo', 102.3432);
    $c->setFormatter($formatter);
    echo $c->get('foo');  

    ==> $102.34

**(4) Easy Debugging**  Similar to PHPs print_r() function, the debug()   
method will echo out a listing of each element along with their key, value, and type.  
When applied to object arrays, print_r() can produce pages of output which make understanding  
the contents confusing, while debug() will produce less verbose output.

    $c = new Collection();
    $c->set('a', 123);
    $c->set('b', new DateTime());
    $c->set('c', 'String 1..2..3..');
    echo $c->debug();

    ==> Collection: 
    a : [Integer]  123 
    b : [Object]  DateTime 
    c : [String]  String 1..2..3.. 

*You can also use the toArray() method to get representation of the collection object.  


**(5) Applying a Filter**    
   
    $filter = function($elmt){
        if(strtoupper($elmt)==$elmt){
            return true;
        } else {
            return false;
        }
    };
    $data = array('hello', 'CRUEL', 'World');
    $c = new Collection($data);
    $c->filter($filter);
    $c->debug();

**(6) Map/Reduce**   

    $data = array('a' => 1, 'b'=>2, 'c'=> 3);
    $c = new Collection($data);
    $map = function($elmt){
        $return = $elmt*100;
        return $return;
    };
    $reduce = function($elmt, $init){
        $return = $elmt + $init;
        return $return;
    };
    $c->map($map)->debug();
    echo $c->reduce($reduce, 0);

    => Collection: 
    a : [Integer]  100 
    b : [Integer]  200 
    c : [Integer]  300 

    ==> 600



**(7) Configuration options for your Collection**   
  The defaults for the collection will not throw any exceptions.. you can add/remove    
  elements.  If the element doesn't exist, the collection will return a NULL.    
  Arguments are passed as an associative array:   
    
  boolean **muteErrors** - if true will prevent any exceptions from being thrown   
    the collection object.  
    Default = False    

  boolean **preventOverwrites** - if true will throw an exception if your collection   
    tries to set a variable that has already been set.  (static behavior)   
    Default = false    

  boolean **confirmExists** - if true will throw an exception if you try to retrieve   
    a value that has not been set.   
    Default = false    

  boolean **alertInvalid** - if true will throw an exception if you try to add
    an element that fails the validation rule.
    Default = false. 

    Example:
    $c = new Collection();
    $c->config(array('preventOverwrites'=>true));
    $c->set('foo', 'bar');
    try {
        $c->set('foo', 'newBar');
    } catch (\Exception $e){
        echo "Exception Caught!";
    }
    ==> Exception Caught!


Available Methods:
-----------------
   
* mixed **set** (string $key, mixed $elmt)    
Adds an element to the collection identified by a unique key.

* mixed **get** (string $key)   
  Retrieves an element from the collection.
  
* array **keys** ( )   
  Returns an array of all the keys in the collection.

* boolean **exist** (string $key)   
  Returns TRUE if the there exists an element associated with   
  that key and false otherwise.


* collection **remove** (mixed $qry)   
  Removes all elements from the collection matching the query.

* collection **clear** ( )   
  Removes all elements from the collection,

* integer **count** ( )   
  Returns the number of elements in the collection.

* new collection **copy** ( )   
  Returns a clone of the current collection object.

* collection **append** (array $loadData)   
  Adds each element of the array to the collection.

* collection **filter** (mixed $qry)   
  Loops through each element in the collection, and discards
    elements that fail to match the query.

* new collection **pluck** (mixed $qry)   
  Loops through each element in the collection, and includes  
  all elements that pass the query to a new collection object
  that is returned.

>> Note: The difference between **filter** and **pluck** is that 
   **filter** will remove elements from the current collection while 
   **pluck** will return a new object

* collection **map** (Closure $function)   
  Applies the function to each element in the collection

* mixed **reduce** (Closure $function, $initialValue)   
  Applies the function to each consecitive elements to combine  
  the collection into a single value.

* array **toArray** ( )   
  Returns an associative array representing the collection

* string **debug** (boolean $echo, string $break)   
  Returns a string listing the elements and their type

* collection **config** (array $options)   
  Sets the configuration variables for the collection object.    
  See the Example on configuration for more information.

* collection **setErrorHandler** (closure $function)    
  Sets the Error Handler function for the collection.

* collection **setValidator** (closure $function, $applyToCurrent = true)   
  Sets the validator function.  By default, the validator is applied to all   
  current and future elements of the collection.

* collection **setFormatter** (closure $function)   
   Sets the format function of the collection that is applied to the elment   
   as it is extracted from the collection.


Licensing:
----------

This software is released under the MIT License (MIT)


Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so.


THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.