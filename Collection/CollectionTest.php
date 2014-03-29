<?php
namespace Swimson\Utility\Collection;

require('Collection.php');

class MockObject{
    
    const MOCK_CONSTANT = null;
    
    private $mockPrivateProperty = null;
    
    private function mockPrivateMethod()
    {
        return null;
    }
    
    public function mockPublicMethod()
    {
        return null;
    }
    
}


class CollectionTest extends \PHPUnit_Framework_TestCase{

    /* private properties */
    private $c = null;
    private $a = array();
    private $a1 = array();
    private $a2 = array();
    private $v1 = null;


    /* fixture setup */
    public function setUp()
    {
        
        $c = new Collection;
        
        // add a string element
        $c->set('string1', 'I AM A STRING');
        $c->set('boolean1', true);
        $c->set('integer1', (int) 3);
        $c->set('float1', (float) 3.14);
        $c->set('null1', null);
        $c->set('array1', array(1,2,3,4));
        $c->set('object1', new MockObject());
        $c->set('resource1', fopen('Collection.php', 'r'));
        $c->set('callable1', function($a,$b){
            return $a+$b; 
        });

        $this->c = $c;
        
        $a = array(1,2,3,4,5,6,7,8,9,10);
        $this->a = $a;
        
        $a1 = array(
            'string1' => 'a',
            'string2' => 'b',
            'string3' => 'c',
            'string4' => 'd',
            'string5' => 'e'
            );
        
        $this->a1 = $a1;
            
        $a2 = array(
            'string6' => 'a',
            'string7' => 'f'
            );
        
        $this->a2 = $a2;
        
        $v1 = function($elmt)
        {
            if($elmt == 'a' || $elmt == 'b'){
                return true;
            } else {
                return false;
            }
        };
        
        $this->v1 = $v1;
        
    }
    
    /* unit tests */
    public function testClassDefinition(){
        $c = $this->c;
        
        $this->assertInstanceOf('Swimson\Utility\Data\Collection', $c);
        
        // type constants
        $this->assertEquals($c::C_ARRAY, collection::C_ARRAY);
        $this->assertEquals($c::C_OBJECT, collection::C_OBJECT);
        $this->assertEquals($c::C_BOOLEAN, collection::C_BOOLEAN);
        $this->assertEquals($c::C_INTEGER, collection::C_INTEGER);
        $this->assertEquals($c::C_FLOAT, collection::C_FLOAT);
        $this->assertEquals($c::C_STRING, collection::C_STRING);
        $this->assertEquals($c::C_NULL, collection::C_NULL);
        $this->assertEquals($c::C_RESOURCE, collection::C_RESOURCE);
        $this->assertEquals($c::C_CLOSURE, collection::C_CLOSURE);
        
        // exception constants
        $this->assertEquals($c::E_UKNOWN_TYPE, collection::E_UKNOWN_TYPE);
        $this->assertEquals($c::E_SEARCH_ARG, collection::E_SEARCH_ARG);
        $this->assertEquals($c::E_VALIDATION, collection::E_VALIDATION);
        $this->assertEquals($c::E_OVERWRITE, collection::E_OVERWRITE);
        $this->assertEquals($c::E_DOES_NOT_EXIST, collection::E_DOES_NOT_EXIST);
        

        // properties
        $this->assertTrue(property_exists($c, 'collection'));
        $this->assertTrue(property_exists($c, 'validator'));
        $this->assertTrue(property_exists($c, 'errorHandler'));
        $this->assertTrue(property_exists($c, 'formatFunction'));
        
        // options
        $this->assertTrue(property_exists($c, 'optionMuteAllErrors'));
        $this->assertTrue(property_exists($c, 'optionPreventOverwrites'));
        $this->assertTrue(property_exists($c, 'optionConfirmExists'));
        $this->assertTrue(property_exists($c, 'optionAlertInvalid'));
        
        
        // magic methods
        $this->assertTrue(method_exists($c, '__construct'), 'Method _construct does not exist');
        
        
        // getter / setter
        $this->assertTrue(method_exists($c, 'set'), 'Method set does not exist');
        $this->assertTrue(method_exists($c, 'get'), 'Method get does not exist');
        
        // collection methods
        $this->assertTrue(method_exists($c, 'keys'), 'method keys does not exist');
        $this->assertTrue(method_exists($c, 'exist'), 'method exist does not exist');
        $this->assertTrue(method_exists($c, 'remove'), 'method remove does not exist');
        $this->assertTrue(method_exists($c, 'clear'), 'method clear does not exist');
        $this->assertTrue(method_exists($c, 'count'), 'method count does not exist');
        $this->assertTrue(method_exists($c, 'copy'), 'method copy does not exist');
        $this->assertTrue(method_exists($c, 'append'), 'method append does not exist');
        
        
        // utility methods
        $this->assertTrue(method_exists($c, 'filter'), 'method filter does not exist');
        $this->assertTrue(method_exists($c, 'pluck'), 'method pluck does not exist');
        $this->assertTrue(method_exists($c, 'map'), 'method map does not exist');
        $this->assertTrue(method_exists($c, 'reduce'), 'method reduce does not exist');
        $this->assertTrue(method_exists($c, 'toArray'), 'method toArray does not exist');
        $this->assertTrue(method_exists($c, 'debug'), 'method echo does not exist');
        $this->assertTrue(method_exists($c, 'config'), 'method config does not exist');
        
        // callback methods
        $this->assertTrue(method_exists($c, 'setErrorHandler'), 'method setErrorHandler does not exist');
        $this->assertTrue(method_exists($c, 'setValidator'), 'method setValidator does not exist');
        $this->assertTrue(method_exists($c, 'setFormatter'), 'method setFormatter does not exist');
        
        // public helper methods
        $this->assertTrue(method_exists($c, 'validate'), 'method validate does not exist');
        $this->assertTrue(method_exists($c, 'format'), 'method format does not exist');
        
        // private helper methods
        $this->assertTrue(method_exists($c, 'detType'), 'method detType does not exist');
        $this->assertTrue(method_exists($c, 'search'), 'method search does not exist');
        $this->assertTrue(method_exists($c, 'error'), 'method error does not exist');
        
    }
    
    public function testSuccessfulSetGet(){
        $c = new collection;
        
        // test elements
        $string1    = 'I AM A STRING';
        $boolean1   = true;
        $integer1   = (int) 3;
        $float1     = (float) 3.14;
        $null1      = null;
        $array1     = array(1,2,3,4);
        $object1    = new mockObject();
        $resource1  = fopen('Collection.php','r');
        $callable1  = function($a,$b){
            return $a+$b; 
        };
        
        // add a string element
        $c->set('string1',$string1);
        $this->assertEquals(1,  $c->count(),    'Unable to set string element');
        $this->assertSame($c->get('string1'),  $string1,    'Unable to get string element');
        
        // add a boolean element
        $c->set('boolean1',$boolean1);
        $this->assertEquals(2,  $c->count(),     'Unable to set boolean element');
        $this->assertSame($c->get('boolean1'),  $boolean1,    'Unable to get boolean element');
        
        // add an integer element
        $c->set('integer1',$integer1);
        $this->assertEquals(3,  $c->count(),     'Unable to set integer element');
        $this->assertSame($c->get('integer1'),  $integer1,    'Unable to get integer element');
        
        // add a decimal element
        $c->set('float1',$float1);
        $this->assertEquals(4,  $c->count(),     'Unable to set floating decimal element');
        $this->assertSame($c->get('float1'),  $float1,    'Unable to get floating decimal element');
        
        // add a null element
        $c->set('null1', $null1);
        $this->assertEquals(5,  $c->count(),     'Unable to set null element');
        $this->assertSame($c->get('null1'),  $null1,    'Unable to get null element');
        
        // add an array element
        $c->set('array1',$array1);
        $this->assertEquals(6,  $c->count(),     'Unable to set array element');
        $this->assertSame($c->get('array1'),  $array1,    'Unable to get array element');
        
        // add an object element
        $c->set('object1',$object1);
        $this->assertEquals(7,  $c->count(),     'Unable to set object element');
        $this->assertSame($c->get('object1'),  $object1,    'Unable to get object element');
        
        // add a resource element
        $c->set('resource1',$resource1);
        $this->assertEquals(8,  $c->count(),     'Unable to set resource element');
        $this->assertSame($c->get('resource1'),  $resource1,    'Unable to get resource element');
        
        // add a callable element
        $c->set('callable1',$callable1);
        $this->assertEquals(9,  $c->count(),     'Unable to set callable element');
        $this->assertSame($c->get('callable1'),  $callable1,    'Unable to get callable element');
       
    }
    
    public function testSuccessfulConstruct()
    {
        $a1 = $this->a1;
        $v1 = $this->v1;
        
        // loading from associative array
        $c1 = new Collection($a1, null, null, null, array('muteErrors' => true));
        $this->assertSame($c1->count(), 5);
    }
    
    public function testSuccessfulAppend()
    {
        
        $a1 = $this->a1;
        $a2 = $this->a2;
        $v1 = $this->v1;
        
         // construction with validator but without load data
        $c3 = new Collection(array(), $v1, null, null, array('muteErrors'=>true));
        $c3->append($a1);
        $this->assertSame($c3->count(), 2);
        
        // confirm that loading is idempotent
        $c4 = new Collection;
        $c4->append($a1)->append($a1)->append($a1);
        $this->assertSame($c4->count(), 5);
        
        // loading two sets of data
        $c5 = new Collection;
        $c5->append($a1)->append($a2);
        $this->assertSame($c5->count(), 7);
        $c5->setValidator($v1);
        $this->assertSame($c5->count(), 3);
        
        // loading non associative array
        $a3 = array('a','b','c');
        $c6 = new Collection($a3);
        $this->assertSame($c6->count(), 3);

        
    }
    
    public function testSuccessfulKeys()
    {
        $a1 = $this->a1;
        
        $c1 = new Collection($a1);
        $keys = $c1->keys();
        $this->assertSame($keys, array('string1','string2','string3','string4','string5'));
        
    }
    
    public function testToArray()
    {
        $c = $this->c;
        $a = $c->toArray();
        $this->assertTrue(is_array($a));
        $this->assertTrue(count($a)==9);
    }
    
    public function testCopy()
    {
        $c1 = $this->c;
        $c2 = $c1->copy();
        $this->assertTrue($c1 == $c2);
        $this->assertFalse($c1 === $c2);
    }
    
    public function testSuccessfulFilter()
    {
        $a = $this->a;
        $filterEven = function($elmt)
        {
            if ($elmt % 2 == 0) {
                return true;
            } else {
                return false;
            }
        };
        
        $c1 = new Collection($a);
        $c1->filter($filterEven);
        $this->assertSame($c1->toArray(), array(1=>2, 3=>4, 5=>6, 7=>8, 9=>10));
        $this->assertSame($c1->toArray(false), array(2, 4, 6, 8, 10));
        
        $filterOdd = function($elmt)
        {
            if ($elmt % 2 != 0) {
                return true;
            } else {
                return false;
            }
        };
        
        $c2 = new Collection($a);
        $c2->filter($filterOdd);
        $this->assertSame($c2->toArray(), array(0=>1, 2=>3, 4=>5, 6=>7, 8=>9));
        $this->assertSame($c2->toArray(false), array(1, 3, 5, 7, 9));
        
    }
    
    public function testSuccessfulPluck()
    {
        $a = $this->a;
        $filterEven = function($elmt)
        {
            if ($elmt % 2 == 0) {
                return true;
            } else {
                return false;
            }
        };
        
        $c1 = new Collection($a);
        $c2 = $c1->copy();
        $c3 = $c1->pluck($filterEven);

    }
    
    public function testSuccessfulExist()
    {
        $c = $this->c;
        $this->assertTrue($c->exist('string1'));
        $this->assertFalse($c->exist('string2'));
    }
    
    public function testSuccessfulRemove()
    {
        $c = $this->c;
        $this->assertSame($c->count(),9);
        
        // test removing single elemt
        $c->remove('string1');
        $this->assertSame($c->count(),8);
        
        // test removing array of keys
        $c->remove(array('callable1','resource1'));
        $this->assertSame($c->count(),6);
        
        $f = function($elmt){
            $return = false;
            if(is_numeric($elmt)){
                $return = true;
            }
            return $return;
        };
        $c->remove($f);
        $this->assertSame($c->count(),4);
        
    }
    
    public function testSuccessfulClear()
    {
        $c = $this->c;
        $this->assertSame($c->count(),9);
        
        $c->clear();
        $this->assertSame($c->count(),0);
        
    }
    
    public function testSuccessfulMap()
    {
        
        $a = $this->a;
        $c = new Collection($a);
        
        $m = function($elmt){
            return $elmt*2;
        };
        $c->map($m);
        
        $this->assertSame($c->toArray(false),array(2,4,6,8,10,12,14,16,18,20));
        
    }
    
    public function testSuccessfulReduce()
    {

        // reducing numeric values
        $a = $this->a;
        $c = new Collection($a);
        
        $r1 = function($val, $elmt){
            return $val + $elmt;
        };
        
        $this->assertSame($c->reduce($r1,0), 55);
        $this->assertSame($c->reduce($r1,10), 65);
        
        // reducing string data
        $a1 = $this->a1;
        $c2 = new Collection($a1);
        $r2 = function($val, $elmt){
            return $val.$elmt;
        };
        $this->assertSame($c2->reduce($r2,''), 'abcde');
    }
    
    public function testSuccessfulPreventOverwrite()
    {
        // should throw exception
        $c = new Collection(null, null, null, null, array('preventOverwrites'=>true));
        try {
            $c->set('string1','this is a string');
            $c->set('string1','this is a string');
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), Collection::E_OVERWRITE);
        }
        
        // should not throw option
        $d = new Collection(null, null, null, null, array('preventOverwrites'=>false));
        $d->set('string1','this is a string');
        $d->set('string1','this is a string');
    }
    
    public function testSuccessfulConfirmExists()
    {
        // should throw exception
        $c = new Collection(null, null, null, null, array('confirmExists'=>true));
        try {
            $c->get('string1');
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), Collection::E_DOES_NOT_EXIST);
        }
        
        // should not throw option
        $c = new Collection;
        $c->get('thisDoesNotExist');
        
    }   
    
    public function testSuccessfulErrorHandler()
    {
        $eh = function($error){
          throw new \Exception('TESTERROR');  
        };
        
        $c = new Collection(null, null, null, $eh, array('confirmExists'=>true));
        try {
            $c->get('string1');
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), 'TESTERROR');
        }
        
    }
    
    public function testSuccessfulSetFormatter()
    {
        $f = function($elmt){
            $return = "$".number_format($elmt,2);
            return $return;
        };
        
        $c = new Collection;
        $c->setFormatter($f);
        
        $c->set('a',123.456);
        $c->set('b',145.345);
        
        $this->assertEquals($c->get('a'), '$123.46');
        $this->assertEquals($c->get('b'), '$145.35');
        
        $r = function($elmt, $init){
            $return = $elmt + $init;
            return $return;
        };
        $this->assertEquals($c->reduce($r,0), '$268.80');
    }
}   