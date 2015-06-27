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

use PHPUnit_Framework_TestCase;
use GanbaroDigital\Reflection\Caches\AllMatchingTypesListCache;
use GanbaroDigital\UnitTestHelpers\ClassesAndObjects\InvokeMethod;

interface AllMatchingTypesListTest_Interface1 { }
interface AllMatchingTypesListTest_Interface2 { }
interface AllMatchingTypesListTest_Interface3 { }

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
     * @covers ::fromArray
     * @covers ::fromMixed
     * @covers ::fromObject
     * @covers ::getObjectConditionalTypes
     * @covers ::fromString
     * @covers ::fromClass
     * @covers ::fromClassName
     * @covers ::checkAcceptableClassName
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
            [ null,  [ 'NULL' , 'Mixed' ] ],
            [ [ 1,2,3 ], [ 'Array', 'Traversable', 'Mixed' ] ],
            [ true, [ 'Boolean', 'Mixed' ] ],
            [ false, [ 'Boolean', 'Mixed' ] ],
            [ 0.0, [ 'Double', 'Mixed' ] ],
            [ 0, [ 'Integer', 'Mixed' ] ],
            [ 1, [ 'Integer', 'Mixed' ] ],
            [ new SimpleType(), [ SimpleType::class, 'Object', 'Callable', 'Mixed' ] ],
            [ '100', [ 'String', 'Mixed' ] ],
        ];
    }

    /**
     * @covers ::__invoke
     * @covers ::fromObject
     * @covers ::getObjectConditionalTypes
     * @covers ::fromClass
     * @covers ::fromClassName
     * @covers ::checkAcceptableClassName
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
                    'Mixed'
                ]
            ],
            [
                new AllMatchingTypesListTest_Target2,
                [
                    AllMatchingTypesListTest_Target2::class,
                    AllMatchingTypesListTest_Interface2::class,
                    'Object',
                    'Mixed'
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
                    'Mixed'
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
                    'Mixed'
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
            'Mixed'
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = AllMatchingTypesList::fromArray($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromObject
     * @covers ::checkAcceptableObject
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
            'Object',
            'Callable',
            'Mixed'
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = AllMatchingTypesList::fromObject($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromObject
     * @covers ::checkAcceptableObject
     * @covers ::buildObjectTypeList
     * @covers ::getObjectConditionalTypes
     */
    public function testDetectsStringyObjects()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = new AllMatchingTypesListTest_StringTarget;
        $expectedResult = [
            AllMatchingTypesListTest_StringTarget::class,
            'Object',
            'String',
            'Mixed'
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = AllMatchingTypesList::fromObject($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromString
     * @covers ::fromClass
     * @covers ::checkAcceptableClassName
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
            'Mixed'
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = AllMatchingTypesList::fromString($data);

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

    /**
     * @covers ::fromArray
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'NULL' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromArray
     */
    public function testCannotPassNullsIntoStaticArrayMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromArray(null);
    }

    /**
     * @covers ::fromArray
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'boolean' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromArray
     */
    public function testCannotPassFalseIntoStaticArrayMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromArray(false);
    }

    /**
     * @covers ::fromArray
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'boolean' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromArray
     */
    public function testCannotPassTrueIntoStaticArrayMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromArray(true);
    }

    /**
     * @covers ::fromArray
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'object' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromArray
     */
    public function testCannotPassCallableIntoStaticArrayMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromArray(function() { });
    }

    /**
     * @covers ::fromArray
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'integer' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromArray
     */
    public function testCannotPassIntsIntoStaticArrayMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromArray(100);
    }

    /**
     * @covers ::fromArray
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'double' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromArray
     */
    public function testCannotPassDoubleIntoStaticArrayMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromArray(3.1415927);
    }

    /**
     * @covers ::fromArray
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'object' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromArray
     */
    public function testCannotPassObjectIntoStaticArrayMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromArray(new \stdClass);
    }

    /**
     * @covers ::fromArray
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'string' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromArray
     */
    public function testCannotPassStringIntoStaticArrayMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromArray("3.1415927");
    }


    /**
     * @covers ::fromClass
     * @covers ::checkAcceptableClassName
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'NULL' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromClass
     */
    public function testCannotPassNullsIntoStaticClassMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromClass(null);
    }

    /**
     * @covers ::fromClass
     * @covers ::checkAcceptableClassName
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'boolean' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromClass
     */
    public function testCannotPassFalseIntoStaticClassMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromClass(false);
    }

    /**
     * @covers ::fromClass
     * @covers ::checkAcceptableClassName
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'boolean' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromClass
     */
    public function testCannotPassTrueIntoStaticClassMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromClass(true);
    }

    /**
     * @covers ::fromClass
     * @covers ::checkAcceptableClassName
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'object' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromClass
     */
    public function testCannotPassCallableIntoStaticClassMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromClass(function() { });
    }

    /**
     * @covers ::fromClass
     * @covers ::checkAcceptableClassName
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'integer' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromClass
     */
    public function testCannotPassIntsIntoStaticClassMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromClass(100);
    }

    /**
     * @covers ::fromClass
     * @covers ::checkAcceptableClassName
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'double' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromClass
     */
    public function testCannotPassDoubleIntoStaticClassMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromClass(3.1415927);
    }

    /**
     * @covers ::fromClass
     * @covers ::checkAcceptableClassName
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'object' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromClass
     */
    public function testCannotPassObjectIntoStaticClassMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromClass(new \stdClass);
    }

    /**
     * @covers ::fromClass
     * @covers ::checkAcceptableClassName
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'array' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromClass
     */
    public function testCannotPassArrayIntoStaticClassMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromClass(["3.1415927"]);
    }

    /**
     * @covers ::fromClass
     * @covers ::checkAcceptableClassName
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_NoSuchClass
     * @expectedExceptionMessage no such class 'ThisIsANonsenseClass'
     */
    public function testCannotPassNonexistentClassIntoStaticClassMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromClass("ThisIsANonsenseClass");
    }


    /**
     * @covers ::fromObject
     * @covers ::checkAcceptableObject
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'NULL' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromObject
     */
    public function testCannotPassNullsIntoStaticObjectMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromObject(null);
    }

    /**
     * @covers ::fromObject
     * @covers ::checkAcceptableObject
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'boolean' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromObject
     */
    public function testCannotPassFalseIntoStaticObjectMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromObject(false);
    }

    /**
     * @covers ::fromObject
     * @covers ::checkAcceptableObject
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'boolean' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromObject
     */
    public function testCannotPassTrueIntoStaticObjectMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromObject(true);
    }

    /**
     * @covers ::fromObject
     * @covers ::checkAcceptableObject
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'integer' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromObject
     */
    public function testCannotPassIntsIntoStaticObjectMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromObject(100);
    }

    /**
     * @covers ::fromObject
     * @covers ::checkAcceptableObject
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'double' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromObject
     */
    public function testCannotPassDoubleIntoStaticObjectMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromObject(3.1415927);
    }

    /**
     * @covers ::fromObject
     * @covers ::checkAcceptableObject
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'array' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromObject
     */
    public function testCannotPassArrayIntoStaticObjectMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromObject([]);
    }

    /**
     * @covers ::fromObject
     * @covers ::checkAcceptableObject
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'string' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromObject
     */
    public function testCannotPassStringIntoStaticObjectMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromObject("3.1415927");
    }

    /**
     * @covers ::fromString
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'NULL' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromString
     */
    public function testCannotPassNullsIntoStaticStringMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromString(null);
    }

    /**
     * @covers ::fromString
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'boolean' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromString
     */
    public function testCannotPassFalseIntoStaticStringMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromString(false);
    }

    /**
     * @covers ::fromString
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'boolean' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromString
     */
    public function testCannotPassTrueIntoStaticStringMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromString(true);
    }

    /**
     * @covers ::fromString
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'object' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromString
     */
    public function testCannotPassCallableIntoStaticStringMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromString(function() { });
    }

    /**
     * @covers ::fromString
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'integer' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromString
     */
    public function testCannotPassIntsIntoStaticStringMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromString(100);
    }

    /**
     * @covers ::fromString
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'double' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromString
     */
    public function testCannotPassDoubleIntoStaticStringMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromString(3.1415927);
    }

    /**
     * @covers ::fromString
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'object' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromString
     */
    public function testCannotPassObjectIntoStaticStringMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromString(new \stdClass);
    }

    /**
     * @covers ::fromString
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     * @expectedExceptionMessage type 'array' is not supported by GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList::fromString
     */
    public function testCannotPassArrayIntoStaticStringMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        AllMatchingTypesList::fromString(["3.1415927"]);
    }
}