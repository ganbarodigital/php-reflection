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
use GanbaroDigital\UnitTestHelpers\ClassesAndObjects\InvokeMethod;

interface ItemAllTypesListTest_Interface1 { }
interface ItemAllTypesListTest_Interface2 { }
interface ItemAllTypesListTest_Interface3 { }

class ItemAllTypesListTest_Target1 implements ItemAllTypesListTest_Interface1 { }
class ItemAllTypesListTest_Target2 implements ItemAllTypesListTest_Interface2 { }
class ItemAllTypesListTest_Target1_3 extends ItemAllTypesListTest_Target1 implements ItemAllTypesListTest_Interface3 { }
class ItemAllTypesListTest_Target2_3 extends ItemAllTypesListTest_Target2 implements ItemAllTypesListTest_Interface3 { }

class ItemAllTypesListTest_StringTarget
{
    public function __toString()
    {
        // no action required
    }
}

/**
 * @coversDefaultClass GanbaroDigital\Reflection\ValueBuilders\ItemAllTypesList
 */
class ItemAllTypesListTest extends PHPUnit_Framework_TestCase
{
    /**
     * @coversNone
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // perform the change

        $obj = new ItemAllTypesList();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof ItemAllTypesList);
    }

    /**
     * @covers ::__invoke
     * @covers ::fromArray
     * @covers ::fromMixed
     * @covers ::fromObject
     * @dataProvider provideDataToTest
     */
    public function testCanUseAsObject($data, $expectedTypes)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new ItemAllTypesList();

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
            [ new ItemSimpleType(), [ ItemSimpleType::class, 'Object', 'Callable', 'Mixed' ] ],
            [ '100', [ 'String', 'Mixed' ] ],
        ];
    }

    /**
     * @covers ::__invoke
     * @covers ::fromObject
     * @dataProvider provideTestClasses
     */
    public function testCanGetInterfaceNames($data, $expectedTypes)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new ItemAllTypesList();

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
                new ItemAllTypesListTest_Target1,
                [
                    ItemAllTypesListTest_Target1::class,
                    ItemAllTypesListTest_Interface1::class,
                    'Object',
                    'Mixed'
                ]
            ],
            [
                new ItemAllTypesListTest_Target2,
                [
                    ItemAllTypesListTest_Target2::class,
                    ItemAllTypesListTest_Interface2::class,
                    'Object',
                    'Mixed'
                ]
            ],
            [
                new ItemAllTypesListTest_Target1_3,
                [
                    ItemAllTypesListTest_Target1_3::class,
                    ItemAllTypesListTest_Target1::class,
                    ItemAllTypesListTest_Interface1::class,
                    ItemAllTypesListTest_Interface3::class,
                    'Object',
                    'Mixed'
                ]
            ],
            [
                new ItemAllTypesListTest_Target2_3,
                [
                    ItemAllTypesListTest_Target2_3::class,
                    ItemAllTypesListTest_Target2::class,
                    ItemAllTypesListTest_Interface2::class,
                    ItemAllTypesListTest_Interface3::class,
                    'Object',
                    'Mixed'
                ]
            ],
        ];
    }

    /**
     * @covers ::__invoke
     * @covers ::fromObject
     * @dataProvider provideTestClasses
     */
    public function testHasInternalCacheForObjects($data, $expectedTypes)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new ItemAllTypesList();

        // all instances of ItemAllTypesList have a shared cache
        // we need to reset it for this unit test
        InvokeMethod::onObject($obj, 'resetCache');

        // make sure the cache is definitely empty
        $cache = InvokeMethod::onObject($obj, 'getCache');
        $this->assertEquals([], $cache);

        // ----------------------------------------------------------------
        // perform the change

        // first time around, there is no match in the cache
        $actualTypes1 = $obj($data);

        // this time, we are returning data from the cache
        $actualTypes2 = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        // the cache should have 1 entry, and it should be the data that
        // we expect
        $cache = InvokeMethod::onObject($obj, 'getCache');
        $this->assertEquals([get_class($data) => $expectedTypes], $cache);
    }

    /**
     * @covers ::fromArray
     */
    public function testDetectsCallableArrays()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = [ ItemAllTypesList::class, 'fromArray' ];
        $expectedResult = [
            'Callable',
            'Array',
            'Traversable',
            'Mixed'
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = ItemAllTypesList::fromArray($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromObject
     */
    public function testDetectsInvokeableObjects()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = new ItemAllTypesList;
        $expectedResult = [
            ItemAllTypesList::class,
            'Object',
            'Callable',
            'Mixed'
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = ItemAllTypesList::fromObject($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromObject
     */
    public function testDetectsStringyObjects()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = new ItemAllTypesListTest_StringTarget;
        $expectedResult = [
            ItemAllTypesListTest_StringTarget::class,
            'Object',
            'String',
            'Mixed'
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = ItemAllTypesList::fromObject($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

}