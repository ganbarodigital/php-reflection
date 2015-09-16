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
 * @link      http://code.ganbarodigital.com/php-reflection
 */

namespace GanbaroDigital\Reflection\ValueBuilders;

use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * @coversDefaultClass GanbaroDigital\Reflection\ValueBuilders\ConvertToString
 */
class ConvertToStringTest extends PHPUnit_Framework_TestCase
{
    /**
     * @coversNone
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // perform the change

        $obj = new ConvertToString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof ConvertToString);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideDataToTest
     */
    public function testCanUseAsObject($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new ConvertToString();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::from
     * @dataProvider provideDataToTest
     */
    public function testCanCallStatically($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = ConvertToString::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromArray
     * @dataProvider provideArraysToTest
     */
    public function testCanConvertArrays($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = ConvertToString::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromBoolean
     * @dataProvider provideBooleansToTest
     */
    public function testCanConvertBooleans($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = ConvertToString::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromCallable
     * @dataProvider provideCallablesToTest
     */
    public function testCanConvertCallables($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = ConvertToString::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromString
     * @dataProvider provideDoublesToTest
     */
    public function testCanConvertDoubles($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = ConvertToString::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromString
     * @dataProvider provideIntegersToTest
     */
    public function testCanConvertIntegers($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = ConvertToString::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromNull
     * @dataProvider provideNullsToTest
     */
    public function testCanConvertNulls($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = ConvertToString::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromObject
     * @dataProvider provideObjectsToTest
     */
    public function testCanConvertObjects($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = ConvertToString::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromResource
     * @dataProvider provideResourcesToTest
     */
    public function testCanConvertResources($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = ConvertToString::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromString
     * @dataProvider provideStringsToTest
     */
    public function testCanConvertStrings($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = ConvertToString::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function provideDataToTest()
    {
        return array_merge(
            $this->provideArraysToTest(),
            $this->provideBooleansToTest(),
            $this->provideCallablesToTest(),
            $this->provideDoublesToTest(),
            $this->provideIntegersToTest(),
            $this->provideNullsToTest(),
            $this->provideObjectsToTest(),
            $this->provideResourcesToTest(),
            $this->provideStringsToTest()
        );
    }

    public function provideArraysToTest()
    {
        return [
            [ [ 'jack', 'and', 'jill' ], 'jack and jill' ],
        ];
    }

    public function provideBooleansToTest()
    {
        return [
            [ true, 'true' ],
            [ false, 'false' ],
        ];
    }

    public function provideCallablesToTest()
    {
        return [
            [ [ self::class, 'provideCallablesToTest'], '(callable GanbaroDigital\Reflection\ValueBuilders\ConvertToStringTest::provideCallablesToTest())' ],
            [ new ConvertToString, '(callable GanbaroDigital\Reflection\ValueBuilders\ConvertToString::__invoke())' ],
            [ new ConvertToStringTest_Callable, '(callable GanbaroDigital\Reflection\ValueBuilders\ConvertToStringTest_Callable::__invoke())' ],
            [ function() { }, '(callable Closure::__invoke())' ],
            [ 'is_string', '(callable is_string())' ],
        ];
    }

    public function provideDoublesToTest()
    {
        return [
            [ 0.0, "0" ],
            [ 3.1415927, "3.1415927" ],
        ];
    }

    public function provideIntegersToTest()
    {
        return [
            [ 0, "0" ],
            [ 100, "100" ],
        ];
    }

    public function provideNullsToTest()
    {
        return [
            [ null, "null" ],
        ];
    }

    public function provideObjectsToTest()
    {
        return [
            [ new stdClass, "(object of type stdClass)" ],
        ];
    }

    public function provideResourcesToTest()
    {
        return [
            [ STDIN, "(resource)" ],
        ];
    }

    public function provideStringsToTest()
    {
        return [
            [ "hello, world", "hello, world" ],
            [ new ConvertToStringTest_Stringy, "I am stringy" ],
        ];
    }
}

class ConvertToStringTest_Callable
{
    public function __invoke() { }
}

class ConvertToStringTest_Stringy
{
    public function __toString() { return "I am stringy"; }
}