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
 * @package   Reflection/Checks
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-reflection
 */

namespace GanbaroDigital\Reflection\Checks;

use ArrayObject;
use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * @coversDefaultClass GanbaroDigital\Reflection\Checks\IsEmpty
 */
class IsEmptyTest extends PHPUnit_Framework_TestCase
{
    /**
     * @coversNothing
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $obj = new IsEmpty();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof IsEmpty);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideDataToTest
     */
    public function testCanUseAsObject($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsEmpty;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::check
     * @dataProvider provideDataToTest
     */
    public function testCanCallStatically($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = IsEmpty::check($data, $expectedResult);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::checkNull
     * @dataProvider provideNullToTest
     */
    public function testNullIsAlwaysEmpty($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsEmpty;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::checkArray
     * @dataProvider provideZeroLengthArraysToTest
     */
    public function testZeroLengthArraysAreEmpty($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsEmpty;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::checkArray
     * @dataProvider provideArraysWithEmptyContentToTest
     */
    public function testArraysWithEmptyContentAreEmpty($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsEmpty;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::checkArray
     * @dataProvider provideArraysWithContentToTest
     */
    public function testArraysWithContentAreNotEmpty($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsEmpty;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::checkTraversable
     * @dataProvider provideIteratorsWithNoContentToTest
     */
    public function testIteratorsWithNoContentAreEmpty($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsEmpty;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::checkTraversable
     * @dataProvider provideIteratorsWithEmptyContentToTest
     */
    public function testIteratorsWithEmptyContentAreEmpty($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsEmpty;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::checkTraversable
     * @dataProvider provideIteratorsWithContentToTest
     */
    public function testIteratorsWithContentAreNotEmpty($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsEmpty;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::checkString
     * @dataProvider provideStringsWithWhitespaceToTest
     */
    public function testStringsWithOnlyWhitespaceAreEmpty($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsEmpty;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::checkString
     * @dataProvider provideZeroLengthStringsToTest
     */
    public function testStringsWithZeroLengthAreEmpty($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsEmpty;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::checkString
     * @dataProvider provideStringsWithContentToTest
     */
    public function testStringsWithContentAreNotEmpty($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsEmpty;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::nothingMatchesTheInputType
     * @dataProvider provideEverythingElseToTest
     */
    public function testEverythingElseIsNotEmpty($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsEmpty;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function provideDataToTest()
    {
        return array_merge(
            $this->provideEmptyArraysToTest(),
            $this->provideEmptyValuesToTest(),
            $this->provideArraysWithContentToTest(),
            $this->provideStringsWithContentToTest(),
            $this->provideEverythingElseToTest()
        );
    }

    public function provideEmptyArraysToTest()
    {
        return array_merge(
            $this->provideZeroLengthArraysToTest(),
            $this->provideArraysWithEmptyContentToTest()
        );
    }

    public function provideZeroLengthArraysToTest()
    {
        return [
            [ [], true ],
        ];
    }

    public function provideArraysWithEmptyContentToTest()
    {
        $emptyValues = $this->provideEmptyValuesToTest();

        $retval = [];
        foreach ($emptyValues as $emptyValue) {
            $retval[] = [ [ $emptyValue[0] ], true ];
        }

        return $retval;
    }

    public function provideArraysWithContentToTest()
    {
        $valuesWithContent = $this->provideValuesWithContentToTest();

        $retval = [];
        foreach ($valuesWithContent as $value) {
            $retval[] = [ [ $value[0] ], false ];
        }

        return $retval;
    }

    public function provideIteratorsWithNoContentToTest()
    {
        return [
            [ new ArrayObject(), true ],
            [ new stdClass, true ],
        ];
    }

    public function provideIteratorsWithEmptyContentToTest()
    {
        $emptyValues = array_merge(
            $this->provideEmptyValuesToTest(),
            $this->provideEmptyArraysToTest()
        );

        $retval = [];
        foreach ($emptyValues as $emptyValue) {
            $retval[] = [ new ArrayObject([$emptyValue[0]]), true ];
            $retval[] = [ (object)[[$emptyValue[0]]], true ];
        }

        return $retval;
    }

    public function provideIteratorsWithContentToTest()
    {
        $values = array_merge(
            $this->provideValuesWithContentToTest(),
            $this->provideArraysWithContentToTest(),
            $this->provideEverythingElseToTest()
        );

        $retval = [];
        foreach ($values as $value) {
            $retval[] = [ new ArrayObject([$value[0]]), false ];
            $retval[] = [ (object)[ 'key' => $value[0]], false ];
        }

        return $retval;
    }

    public function provideEmptyValuesToTest()
    {
        return array_merge(
            $this->provideNullToTest(),
            $this->provideZeroLengthArraysToTest(),
            $this->provideEmptyStringsToTest()
        );
    }

    public function provideValuesWithContentToTest()
    {
        return array_merge(
            $this->provideStringsWithContentToTest(),
            $this->provideEverythingElseToTest()
        );
    }

    public function provideNullToTest()
    {
        return [
            [ null, true ],
        ];
    }

    public function provideEmptyStringsToTest()
    {
        return array_merge(
            $this->provideZeroLengthStringsToTest(),
            $this->provideStringsWithWhitespaceToTest()
        );
    }

    public function provideZeroLengthStringsToTest()
    {
        return [
            [ '', true ],
        ];
    }

    public function provideStringsWithWhitespaceToTest()
    {
        return [
            [ ' ', true ],
            [ "\t", true ],
            [ "\n", true ],
            [ "\r", true ],
            [ "\r\n", true ],
        ];
    }

    public function provideStringsWithContentToTest()
    {
        return [
            [ "hello, world!", false ],
        ];
    }

    public function provideEverythingElseToTest()
    {
        return [
            [ [ true ], false ],
            [ [ false ], false ],
            [ [ self::class, 'provideDataToTest'], false ],
            [ true, false ],
            [ false, false ],
            [ function(){}, false ],
            [ 3.1415927, false ],
            [ 100, false ],
            [ 0, false ],
            [ new IsEmpty, false ],
            [ STDIN, false ],
        ];
    }
}
