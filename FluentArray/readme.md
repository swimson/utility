                          Swimson/Utility/FluentArray


What is it?
-----------

Instead of accessing your data through the PHP array notation:

	$foo['alpha']['beta']['omega']

Using FluentArray you can easily access your data using method chaining instead.
	
	$foo->alpha->beta->omega

How it works
------------

FluentArray library works like a factory that provides a fluent interface to your native PHP associative array data.  By using method chaining, you can easily set/get values in your array of any depth.  The class has only one public methods **toArray()** and the rest of the behavior is handled through PHP magic methods for setters and getters.

FluentArray will take each method call and add an additional level to its internal array, allowing you flexibility in designing your data structure.  Even beter, if you try to access data that doesn't exist yet, FluentArray gracefully create the corresponding node with a **null** value instead of throwing a warning.
   
Check It Out
------------

	// (1) Start with an array of data
	$apples => array(
		array(
			'sku' => '23343'
			'type' => 'Granny Smith',
			'price' => .50, 
		),
		array(
			'sku' => '23442'
			'type' => 'Fiji',
			'price' => .62
		)
	)

	// (2) convert it to an object
	$apples = new FluentArray($apples);	

	// (3) Now all of your data is accessible through method chaining
	var_dump($apples->2->sku);

		(string) 23442

	// (4) Feel free to update your data using method chaining.
	$apples->1->price = .55;

	// (4) If you need the updated data as an array, just use the toArray() method
	print_r($apples->toArray());

	array(
		array(
			'sku' => '23343'
			'type' => 'Granny Smith',
			'price' => .55, 
		),
		array(
			'sku' => '23442'
			'type' => 'Fiji',
			'price' => .62
		)
	)


The Latest Version
------------------

Details of the latest version can be found on the at the GitHub page    
https://github.com/swimson/utility


Code Examples:
--------------

**1) Initialization**
    
    $foo = new FluentArray();

Or if you have data available already...

	$values = array( .... );
	$foo = FluentArray($values);

This factor method will return an instance of a FluentArray representing your data.

**2) Setting Values**   
This shopping cart example will explain how to use FluentArray. 

    $shoppingCart = new FluentArray();
    $shoppingCart->userId = 1145;

	// Using the full hierarchy
	$shoppingCart->items->1->type = 'tshirt';
	$shoppingCart->items->1->size = 'XL';
	$shoppingCart->items->1->quantity = 4;

	// Or you can append at a specific node an element already created with FluentArray
	$item2 = new FluentArray();
	$item2->type = 'tshirt';
	$item2->size = 'M';
	$item2->quantity = 1;
	
	$shoppingCart->items->2 = $item2;


In the examples above, all the values were scalar values.  But you can just as easily attach objects.

    $shoppingCart->items->1->date = new DateTime('3/13/2014');
	$shoppingCart->coupon = new AwesomeCouponObject();

Internally, the FluentArray will wrap your data in an object and attach it to the node that you specify.  FluentArray hides this complexity from you, but allows a clean API that you can easily set data and retrieve it back out.


**3) Retrieving Values**   
Reading data out of a FluentArray is also very easy, just switch the order to pull data out.

    $tshirtSize = $shoppingCart->items->1->size;
	var_dump($tshirtSize);

		(string) 'XL'

	$userId = $shoppingCart->userId;
	var_dump($userId);

		(int) 1145

If you try to access a value that has not been set, you will receive a null instead of a warning;

    $username = $shoppingCart->username;
	varr_dump($username);
	
		(null)

And if you access a node that has children, you will get back an FluentArray instance.

    $items = $shoppingCart->items;
	echo get_class($items);
	
		Swimson\Util\FluentArray;

**4) Leveraging PHP Object References**  
By default PHP assigns array variables by value but objects by reference.  We can leverage this to create interesting behavior in our FluentArray.

For instance, consider...

    $item1 = $shoppingCart->items->1;
	$item1->price = 100.50;

Now item1 points back to the original ShoppingCart object, so the price in the original object is also updated automatically.

    var_dummp($shoppingCart->items->1->price);
		
		(float) 100.50

Remember this when working with child nodes.


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