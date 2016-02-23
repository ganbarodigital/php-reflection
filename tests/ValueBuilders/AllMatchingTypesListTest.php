<?php

/**
 * Copyright (c) 2015-present Ganbaro Digital Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Libraries
 * @package   Reflection/ValueBuilders
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-file-system
 */

namespace GanbaroDigital\Reflection\ValueBuilders;

use ArrayObject;
use PHPUnit_Framework_TestCase;
use GanbaroDigital\Reflection\Caches\AllMatchingTypesListCache;
use GanbaroDigital\UnitTestHelpers\ClassesAndObjects\InvokeMethod;
use stdClass;

interface AllMatchingTypesListTest_Interface1 { }
interface AllMatchingTypesListTest_Interface2 { }
interface AllMatchingTypesListTest_Interface3 { }
interface AllMatchingTypesListTest_Interface4 extends AllMatchingTypesListTest_Interface1 { }

class AllMatchingTypesListTest_Target1 implements AllMatchingTypesListTest_Interface1 { }
class AllMatchingTypesListTest_Target2 implements AllMatchingTypesListTest_Interface2 { }
class AllMatchingTypesListTest_Target1_3 extends AllMatchingTypesListTest_Target1 implements AllMatchingTypesListTest_Interface3 { }
class AllMatchingTypesListTest_Target2_3 extends AllMatchingTypesListTest_Target2 implements AllMatchingTypesListTest_Interface3 { }

class AllMatchingTypesListTest_StringTarget
{
    public function __toString()
    {
        // no action required
    }
}

/**
 * @coversDefaultClass GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList
 */
class AllMatchingTypesListTest extends PHPUnit_Framework_TestCase
{
    public function setup()
    {
        // AllMatchingTypesList is backed by a static cache
        //
        // we need to make sure that it is empty at the start of every
        // test
        InvokeMethod::onString(AllMatchingTypesList::class, 'resetCache');
    }

    /**
     * @coversNone
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // perform the change

        $obj = new AllMatchingTypesList();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof AllMatchingTypesList);
    }

    /**
     * @covers ::__invoke
     * @covers ::from
     * @covers ::fromArray
     * @covers ::fromObject
     * @covers ::getObjectConditionalTypes
     * @covers ::fromString
     * @covers ::fromClass
     * @covers ::fromClassName
     * @covers ::buildCombinedClassNameDetails
     * @dataProvider provideDataToTest
     */
    public function testCanUseAsObject($data, $expectedTypes)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new AllMatchingTypesList();

        // ----------------------------------------------------------------
        // perform the change

        $actualTypes = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedTypes, $actualTypes);
    }

    public function provideDataToTest()
    {
        return [
            [ null,  [ 'NULL' , 'EverythingElse' ] ],
            [ [ 1,2,3 ], [ 'Array', 'Traversable', 'EverythingElse' ] ],
            [ true, [ 'Boolean', 'EverythingElse' ] ],
            [ false, [ 'Boolean', 'EverythingElse' ] ],
            [ 0.0, [ 'Double', 'EverythingElse' ] ],
            [ 0, [ 'Integer', 'EverythingElse' ] ],
            [ 1, [ 'Integer', 'EverythingElse' ] ],
            [ new ArrayObject(), [ ArrayObject::class, 'IteratorAggregate', 'Traversable', 'ArrayAccess', 'Serializable', 'Countable', 'Object', 'EverythingElse' ] ],
            [ new SimpleType(), [ SimpleType::class, 'Callable', 'Object', 'EverythingElse' ] ],
            [ (object)[ 'name' => 'test data'], [ 'Traversable', 'stdClass', 'Object', 'EverythingElse' ] ],
            [ '100', [ 'String', 'EverythingElse' ] ],
        ];
    }

    /**
     * @covers ::__invoke
     * @covers ::fromObject
     * @covers ::getObjectConditionalTypes
     * @covers ::fromClass
     * @covers ::fromClassName
     * @covers ::buildCombinedClassNameDetails
     * @dataProvider provideTestClasses
     */
    public function testCanGetInterfaceNames($data, $expectedTypes)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new AllMatchingTypesList();

        // ----------------------------------------------------------------
        // perform the change

        $actualTypes = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedTypes, $actualTypes);
    }

    public function provideTestClasses()
    {
        return [
            [
                new AllMatchingTypesListTest_Target1,
                [
                    AllMatchingTypesListTest_Target1::class,
                    AllMatchingTypesListTest_Interface1::class,
                    'Object',
                    'EverythingElse'
                ]
            ],
            [
                new AllMatchingTypesListTest_Target2,
                [
                    AllMatchingTypesListTest_Target2::class,
                    AllMatchingTypesListTest_Interface2::class,
                    'Object',
                    'EverythingElse'
                ]
            ],
            [
                new AllMatchingTypesListTest_Target1_3,
                [
                    AllMatchingTypesListTest_Target1_3::class,
                    AllMatchingTypesListTest_Target1::class,
                    AllMatchingTypesListTest_Interface1::class,
                    AllMatchingTypesListTest_Interface3::class,
                    'Object',
                    'EverythingElse'
                ]
            ],
            [
                new AllMatchingTypesListTest_Target2_3,
                [
                    AllMatchingTypesListTest_Target2_3::class,
                    AllMatchingTypesListTest_Target2::class,
                    AllMatchingTypesListTest_Interface2::class,
                    AllMatchingTypesListTest_Interface3::class,
                    'Object',
                    'EverythingElse'
                ]
            ],
        ];
    }

    /**
     * @covers ::fromArray
     */
    public function testDetectsCallableArrays()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = [ AllMatchingTypesList::class, 'fromArray' ];
        $expectedResult = [
            'Callable',
            'Array',
            'Traversable',
            'EverythingElse'
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = AllMatchingTypesList::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromObject
     * @covers ::buildObjectTypeList
     * @covers ::getObjectConditionalTypes
     */
    public function testDetectsInvokeableObjects()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = new AllMatchingTypesList;
        $expectedResult = [
            AllMatchingTypesList::class,
            AllMatchingTypesListCache::class,
            'Callable',
            'Object',
            'EverythingElse'
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = AllMatchingTypesList::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromObject
     * @covers ::buildObjectTypeList
     * @covers ::getObjectConditionalTypes
     */
    public function testDetectsStringyObjects()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = new AllMatchingTypesListTest_StringTarget;
        $expectedResult = [
            AllMatchingTypesListTest_StringTarget::class
        ];
        // HHVM currently adds this class to the class hierarchy
        if (defined('HHVM_VERSION')) {
            $expectedResult[] = 'Stringish';
        }
        $expectedResult = array_merge($expectedResult, [
            'String',
            'Object',
            'EverythingElse'
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = AllMatchingTypesList::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromObject
     * @covers ::buildObjectTypeList
     * @covers ::getObjectSpecialTypes
     * @covers ::getObjectConditionalTypes
     */
    public function testTreatsStdclassAsTraversable()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = new stdClass;
        $expectedResult = [
            'Traversable',
            'stdClass',
            'Object',
            'EverythingElse',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = AllMatchingTypesList::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromString
     * @covers ::fromClass
     * @covers ::buildCombinedClassNameDetails
     */
    public function testDetectsStringyClassnames()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = AllMatchingTypesListTest_StringTarget::class;
        $expectedResult = [
            AllMatchingTypesListTest_StringTarget::class,
            'Class',
            'String',
            'EverythingElse'
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = AllMatchingTypesList::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromString
     */
    public function testDetectsCallableStrings()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = 'is_string';
        $expectedResult = [
            'Callable',
            'String',
            'EverythingElse'
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = AllMatchingTypesList::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromClass
     */
    public function testReadsFromInternalCacheForClasses()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new AllMatchingTypesList;

        // our setup() method guarantees that we start with an empty cache
        $this->assertTrue(empty(InvokeMethod::onObject($obj, 'getCache')));

        $cacheKey = get_class($obj) . '::class';

        // we're going to warm the cache up
        $expectedResult = [ 'alfred' ];
        InvokeMethod::onObject($obj, 'setInCache', [ $cacheKey, $expectedResult ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj(get_class($obj));

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromClass
     */
    public function testWritesToInternalCacheForClasses()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new AllMatchingTypesList();

        // our setup() method guarantees that we start with an empty cache
        $this->assertTrue(empty(InvokeMethod::onObject($obj, 'getCache')));

        $cacheKey = get_class($obj) . '::class';

        // ----------------------------------------------------------------
        // perform the change

        $result = $obj(get_class($obj));

        // ----------------------------------------------------------------
        // test the results

        $cache = InvokeMethod::onObject($obj, 'getCache');
        $this->assertFalse(empty($cache));
        $this->assertEquals([ $cacheKey => $result ], $cache);
    }

    /**
     * @covers ::fromObject
     * @covers ::getObjectFromCache
     * @covers ::getObjectCacheName
     */
    public function testReadsFromInternalCacheForObjects()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new AllMatchingTypesList();

        // our setup() method guarantees that we start with an empty cache
        $this->assertTrue(empty(InvokeMethod::onObject($obj, 'getCache')));

        $cacheKey = get_class($obj) . '::object';

        // we're going to warm the cache up
        $expectedResult = [ 'alfred' ];
        InvokeMethod::onObject($obj, 'setInCache', [ $cacheKey, $expectedResult ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($obj);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromObject
     * @covers ::setObjectInCache
     * @covers ::getObjectCacheName
     */
    public function testWritesToInternalCacheForObjects()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new AllMatchingTypesList();

        // our setup() method guarantees that we start with an empty cache
        $this->assertTrue(empty(InvokeMethod::onObject($obj, 'getCache')));

        // ----------------------------------------------------------------
        // perform the change

        $result = $obj($obj);

        // ----------------------------------------------------------------
        // test the results

        $cache = InvokeMethod::onObject($obj, 'getCache');
        $this->assertFalse(empty($cache));
        $this->assertEquals([get_class($obj) . '::object' => $result ], $cache);
    }

}
