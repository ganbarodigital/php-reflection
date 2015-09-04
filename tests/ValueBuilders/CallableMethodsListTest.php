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

class CallableMethodsListTest_Target1
{
    public function objectMethod1() { }
    protected function objectMethod2() { }
    private function objectMethod3() { }
    public static function staticMethod1() { }
    protected static function staticMethod2() { }
    private static function staticMethod3() { }
}

/**
 * @coversDefaultClass GanbaroDigital\Reflection\ValueBuilders\CallableMethodsList
 */
class CallableMethodsListTest extends PHPUnit_Framework_TestCase
{
    public function setup()
    {
        // we need to empty the internal cache before each unit test
        InvokeMethod::onString(CallableMethodsList::class, 'resetCache');
    }

    /**
     * @coversNone
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $obj = new CallableMethodsList;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof CallableMethodsList);
    }

    /**
     * @covers ::__invoke
     * @covers ::from
     * @covers ::fromString
     * @covers ::fromClassName
     * @covers ::fromObject
     * @covers ::getPublicMethodsFromClass
     * @covers ::filterMethodsByStaticness
     * @dataProvider provideTargetsToFilter
     */
    public function testCanUseAsObject($target, $expectedMethods)
    {
        // ----------------------------------------------------------------
        // setup your test

        $filter = new CallableMethodsList;

        // ----------------------------------------------------------------
        // perform the change

        $actualMethods = $filter($target);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedMethods, $actualMethods);
    }

    public function provideTargetsToFilter()
    {
        // the duplication here is to prove that the underlying cache
        // doesn't affect the return values
        return [
            [ CallableMethodsListTest_Target1::class, [ 'staticMethod1' => 'staticMethod1' ] ],
            [ CallableMethodsListTest_Target1::class, [ 'staticMethod1' => 'staticMethod1' ] ],
            [ new \stdClass, [ ] ],
            [ new \stdClass, [ ] ],
            [ new CallableMethodsListTest_Target1, [ 'objectMethod1' => 'objectMethod1' ] ],
            [ new CallableMethodsListTest_Target1, [ 'objectMethod1' => 'objectMethod1' ] ],
        ];
    }

    /**
     * @covers ::fromString
     * @covers ::fromClassName
     * @covers ::getPublicMethodsFromClass
     * @covers ::filterMethodsByStaticness
     */
    public function testCanGetMethodsFromClass()
    {
        // ----------------------------------------------------------------
        // setup your test

        $target = new CallableMethodsListTest_Target1;
        $expectedMethods = [
            'staticMethod1' => 'staticMethod1'
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualMethods = CallableMethodsList::fromString(get_class($target));

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedMethods, $actualMethods);
    }

    /**
     * @covers ::fromObject
     * @covers ::getPublicMethodsFromClass
     * @covers ::filterMethodsByStaticness
     */
    public function testCanGetMethodsFromObject()
    {
        // ----------------------------------------------------------------
        // setup your test

        $target = new CallableMethodsListTest_Target1;
        $expectedMethods = [
            'objectMethod1' => 'objectMethod1'
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualMethods = CallableMethodsList::fromObject($target);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedMethods, $actualMethods);
    }

    /**
     * @covers ::__invoke
     * @covers ::from
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     *
     * @dataProvider provideBadDataToTest
     */
    public function testRejectsOtherDataTypesWhenUsedAsObject($target)
    {
        // ----------------------------------------------------------------
        // setup your test

        $filter = new CallableMethodsList;

        // ----------------------------------------------------------------
        // perform the change

        $filter($target);
    }

    /**
     * @covers ::from
     * @covers ::fromObject
     * @covers ::fromClassName
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     *
     * @dataProvider provideBadDataToTest
     */
    public function testRejectsOtherDataTypesWhenCalledStatically($target)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        CallableMethodsList::from($target);
    }

    /**
     * @covers ::fromString
     * @covers ::fromClassName
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     *
     * @dataProvider provideBadDataToTestIncObject
     */
    public function testRejectsOtherDataTypesWhenStaticallyCalledFromString($target)
    {
        // ----------------------------------------------------------------
        // setup your test

        $filter = new CallableMethodsListTest;

        // ----------------------------------------------------------------
        // perform the change

        CallableMethodsList::fromString($target);
    }

    /**
     * @covers ::fromObject
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     *
     * @dataProvider provideBadDataToTestIncString
     */
    public function testRejectsOtherDataTypesWhenStaticallyCalledFromObject($target)
    {
        // ----------------------------------------------------------------
        // setup your test

        $filter = new CallableMethodsListTest;

        // ----------------------------------------------------------------
        // perform the change

        CallableMethodsList::fromObject($target);
    }

    public function provideBadDataToTest()
    {
        return [
            [ null ] ,
            [ true ],
            [ false ],
            [ [ 1,2,4 ] ],
            [ 3.1415927 ],
            [ 100 ],
        ];
    }

    public function provideBadDataToTestIncObject()
    {
        return $this->provideBadDataToTest() + [ [ new \stdClass ] ];
    }

    public function provideBadDataToTestIncString()
    {
        return $this->provideBadDataToTest() + [ [ "hello, world" ] ];
    }

    /**
     * @covers ::fromString
     * @covers ::fromClassName
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_NoSuchClass
     */
    public function testStringsMustBeValidClassname()
    {
        // ----------------------------------------------------------------
        // perform the change

        CallableMethodsList::fromString('NoSuch\\Class\\Exists\\At\\All');
    }

    /**
     * @covers ::getClassCacheName
     * @covers ::getObjectCacheName
     */
    public function testUsesDifferentCacheKeyForClassesAndObjects()
    {
        // ----------------------------------------------------------------
        // setup your test

        $targetClass = CallableMethodsListTest_Target1::class;
        $targetObj   = new CallableMethodsListTest_Target1;

        // ----------------------------------------------------------------
        // perform the change

        $classCacheKey  = InvokeMethod::onString(CallableMethodsList::class, 'getClassCacheName', [ $targetClass ]);
        $objectCacheKey = InvokeMethod::onString(CallableMethodsList::class, 'getObjectCacheName', [ $targetObj ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotEquals($classCacheKey, $objectCacheKey);
    }

    /**
     * @covers ::setClassInCache
     * @covers ::fromClassName
     */
    public function testWritesClassResultsToAnInternalCache()
    {
        $target = CallableMethodsListTest_Target1::class;
        $expectedMethods = [
            'staticMethod1' => 'staticMethod1'
        ];

        // put the data into the cache
        CallableMethodsList::fromString($target);

        // ----------------------------------------------------------------
        // perform the change

        $actualMethods = InvokeMethod::onString(CallableMethodsList::class, 'getClassFromCache', [$target]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedMethods, $actualMethods);
    }

    /**
     * @covers ::getClassFromCache
     * @covers ::setClassInCache
     * @covers ::fromClassName
     */
    public function testReadsClassResultsFromAnInternalCache()
    {
        $target = CallableMethodsListTest_Target1::class;
        $expectedMethods = [
            'garbageMethod1' => 'staticMethod1'
        ];

        // put the data into the cache
        InvokeMethod::onString(CallableMethodsList::class, 'setClassInCache', [$target, $expectedMethods]);

        // ----------------------------------------------------------------
        // perform the change

        $actualMethods2 = CallableMethodsList::fromString($target);

        // ----------------------------------------------------------------
        // test the results

        // this proves that the data we expect is in the internal cache
        $actualMethods1 = InvokeMethod::onString(CallableMethodsList::class, 'getClassFromCache', [$target]);
        $this->assertEquals($expectedMethods, $actualMethods1);

        // this proves that CallableMethodsList uses the internal cache
        // when data is present
        $this->assertEquals($expectedMethods, $actualMethods2);
    }

    /**
     * @covers ::setObjectInCache
     * @covers ::fromObject
     */
    public function testWritesObjectResultsToAnInternalCache()
    {
        $target = new CallableMethodsListTest_Target1;
        $expectedMethods = [
            'objectMethod1' => 'objectMethod1'
        ];

        // put the data into the cache
        CallableMethodsList::fromObject($target);

        // ----------------------------------------------------------------
        // perform the change

        $actualMethods = InvokeMethod::onString(CallableMethodsList::class, 'getObjectFromCache', [$target]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedMethods, $actualMethods);
    }

    /**
     * @covers ::getObjectFromCache
     * @covers ::setObjectInCache
     * @covers ::fromObject
     */
    public function testReadsObjectResultsFromAnInternalCache()
    {
        $target = new CallableMethodsListTest_Target1;
        $expectedMethods = [
            'garbageMethod1' => 'staticMethod1'
        ];

        // put the data into the cache
        InvokeMethod::onString(CallableMethodsList::class, 'setObjectInCache', [$target, $expectedMethods]);

        // ----------------------------------------------------------------
        // perform the change

        $actualMethods2 = CallableMethodsList::fromObject($target);

        // ----------------------------------------------------------------
        // test the results

        // this proves that the data we expect is in the internal cache
        $actualMethods1 = InvokeMethod::onString(CallableMethodsList::class, 'getObjectFromCache', [$target]);
        $this->assertEquals($expectedMethods, $actualMethods1);

        // this proves that CallableMethodsList uses the internal cache
        // when data is present
        $this->assertEquals($expectedMethods, $actualMethods2);
    }

    /**
     * @covers ::buildListOfClassMethods
     */
    public function testBuildsAListOfStaticMethodsWhenExaminingAClass()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedMethods = [
            'staticMethod1' => 'staticMethod1'
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualMethods = InvokeMethod::onString(CallableMethodsList::class, 'buildListOfClassMethods', [ CallableMethodsListTest_Target1::class]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedMethods, $actualMethods);
    }

    /**
     * @covers ::buildListOfObjectMethods
     */
    public function testBuildsAListOfNonStaticMethodsWhenExaminingAnObject()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedMethods = [
            'objectMethod1' => 'objectMethod1'
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualMethods = InvokeMethod::onString(CallableMethodsList::class, 'buildListOfObjectMethods', [ new CallableMethodsListTest_Target1 ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedMethods, $actualMethods);
    }

}