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

// this is the $target to use when we want to test what happens when
// a match is found
class FirstMethodMatchingTypeTest_Target1
{
    public function __invoke() { }
    public static function fromMixed() { }
    public static function fromObject() { }
    public static function fromString() { }
}

// this is the $target to use when we want to test what happens when no
// matches are found
class FirstMethodMatchingTypeTest_Target2
{
}

/**
 * @coversDefaultClass GanbaroDigital\Reflection\ValueBuilders\FirstMethodMatchingType
 */
class FirstMethodMatchingTypeTest extends PHPUnit_Framework_TestCase
{
    public function setup()
    {
        // FirstMethodMatchingType is backed by a static cache
        //
        // we need to make sure that it is empty at the start of every
        // test
        InvokeMethod::onString(FirstMethodMatchingType::class, 'resetCache');
    }

    /**
     * @coversNone
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // perform the change

        $obj = new FirstMethodMatchingType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof FirstMethodMatchingType);
    }

    /**
     * @covers ::__invoke
     * @covers ::fromMixed
     * @dataProvider provideDataToTest
     */
    public function testCanUseAsObject($data, $target, $prefix, $expectedMethod)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new FirstMethodMatchingType();

        // ----------------------------------------------------------------
        // perform the change

        $actualMethod = $obj($data, $target, $prefix);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedMethod, $actualMethod);
    }

    public function provideDataToTest()
    {
        return [
            [ new \stdClass, FirstMethodMatchingTypeTest_Target1::class, 'from', 'fromObject' ],
        ];
    }

    /**
     * @covers ::getCacheName
     */
    public function testClassesAndObjectsHaveDifferentCacheKeys()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new FirstMethodMatchingType;
        $data = new \stdClass;
        $targetObj = new FirstMethodMatchingTypeTest_Target1;
        $targetClass = get_class($targetObj);

        // ----------------------------------------------------------------
        // perform the change

        $actualClassCacheKey = InvokeMethod::onObject($obj, 'getCacheName', [$data, $targetClass]);
        $actualObjCacheKey   = InvokeMethod::onObject($obj, 'getCacheName', [$data, $targetObj]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotEquals($actualClassCacheKey, $actualObjCacheKey);
    }

    /**
     * @covers ::getCacheName
     * @dataProvider provideScalarTypes
     */
    public function testScalarTypesHaveDifferentCacheKeys($data, $expectedCacheKey)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new FirstMethodMatchingType;
        $targetObj = new FirstMethodMatchingTypeTest_Target1;
        $targetClass = get_class($targetObj);

        // ----------------------------------------------------------------
        // perform the change

        $actualClassCacheKey = InvokeMethod::onObject($obj, 'getCacheName', [$data, $targetClass]);
        $actualObjCacheKey   = InvokeMethod::onObject($obj, 'getCacheName', [$data, $targetObj]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedCacheKey . '::class',  $actualClassCacheKey);
        $this->assertEquals($expectedCacheKey . '::object', $actualObjCacheKey);
    }

    public function provideScalarTypes()
    {
        $className = FirstMethodMatchingTypeTest_Target1::class;

        return [
            [ null, $className . '::NULL' ],
            [ true , $className . '::boolean' ],
            [ false, $className . '::boolean' ],
            [ [ 1,2,3], $className . '::array' ],
            [ 3.1415927, $className . '::double' ],
            [ 100, $className . '::integer' ],
            [ "hello, world", $className . '::string' ],
        ];
    }

    /**
     * @covers ::__invoke()
     * @covers ::getMethodFromCache
     * @covers ::setMethodInCache
     */
    public function testWritesResultsToStaticDataCache()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new FirstMethodMatchingType;
        $data = new \stdClass;
        $targetObj = new FirstMethodMatchingTypeTest_Target1;

        $expectedResult = $obj($data, $targetObj, 'from');

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = InvokeMethod::onObject($obj, 'getMethodFromCache', [$data, $targetObj]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromMixed
     * @covers ::getMethodFromCache
     * @covers ::setMethodInCache
     */
    public function testReusesResultsFromStaticDataCache()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new FirstMethodMatchingType;
        $data = new \stdClass;
        $targetObj = new FirstMethodMatchingTypeTest_Target1;

        $expectedResult = "noSuchMethod";

        InvokeMethod::onObject($obj, 'setMethodInCache', [$data, $targetObj, $expectedResult]);

        // prove that the data is in the cache
        $cachedResult = InvokeMethod::onObject($obj, 'getMethodFromCache', [$data, $targetObj]);

        // ----------------------------------------------------------------
        // perform the change

        // $actualResult can only have the value we expect if $obj is
        // reading the data we inserted into the cache
        $actualResult = $obj($data, $targetObj, 'from');

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::findMethodToCall
     * @dataProvider provideDataToTest
     */
    public function testMatchesFirstMethodForType($data, $target, $prefix, $expectedMethod)
    {
        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $actualMethod = InvokeMethod::onString(FirstMethodMatchingType::class, 'findMethodToCall', [ $data, $target, $prefix ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedMethod, $actualMethod);
    }

    /**
     * @covers ::findMethodToCall
     */
    public function testFallsBackToInvokeWhenTargetIsAnObject()
    {
        // ----------------------------------------------------------------
        // setup your test

        $target = new FirstMethodMatchingTypeTest_Target1;

        // there is no method on the target object that will match an
        // integer
        $data = 100;

        $expectedMethod = '__invoke';

        // ----------------------------------------------------------------
        // perform the change

        $actualMethod = InvokeMethod::onString(FirstMethodMatchingType::class, 'findMethodToCall', [ $data, $target, 'from' ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedMethod, $actualMethod);
    }

    /**
     * @covers ::findMethodToCall
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     */
    public function testThrowsAnExceptionWhenNoMatchFound()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = 100;
        $target = new FirstMethodMatchingTypeTest_Target2;

        // ----------------------------------------------------------------
        // perform the change

        InvokeMethod::onString(FirstMethodMatchingType::class, 'findMethodToCall', [ $data, $target, 'from'] );
    }

}