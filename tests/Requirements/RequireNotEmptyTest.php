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
 * @package   Reflection/Requirements
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-reflection
 */

namespace GanbaroDigital\Reflection\Requirements;

use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * @coversDefaultClass GanbaroDigital\Reflection\Requirements\RequireNotEmpty
 */
class RequireNotEmptyTest extends PHPUnit_Framework_TestCase
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

        $obj = new RequireNotEmpty;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof RequireNotEmpty);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideDataWithContentToTest
     */
    public function testCanUseAsObject($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new RequireNotEmpty;

        // ----------------------------------------------------------------
        // perform the change

        $obj('$item', $item);
    }

    /**
     * @covers ::check
     * @dataProvider provideDataWithContentToTest
     */
    public function testCanCallStatically($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        RequireNotEmpty::check('$item', $item);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideDataWithNoContentToTest
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_DataCannotBeEmpty
     */
    public function testRejectsNonNullsWhenUsedAsObject($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new RequireNotEmpty;

        // ----------------------------------------------------------------
        // perform the change

        $obj('$item', $item);
    }

    /**
     * @covers ::check
     * @dataProvider provideDataWithNoContentToTest
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_DataCannotBeEmpty
     */
    public function testRejectsNonNullsWhenCalledStatically($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        RequireNotEmpty::check('$item', $item);
    }

    public function provideDataWithContentToTest()
    {
        return array_merge(
            $this->provideArraysWithContentToTest(),
            $this->provideStringsWithContentToTest(),
            $this->provideEverythingElseToTest()
        );
    }

    public function provideDataWithNoContentToTest()
    {
        return array_merge(
            $this->provideEmptyArraysToTest(),
            $this->provideEmptyValuesToTest()
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
            [ [] ],
        ];
    }

    public function provideArraysWithEmptyContentToTest()
    {
        $emptyValues = $this->provideEmptyValuesToTest();

        $retval = [];
        foreach ($emptyValues as $emptyValue) {
            $retval[] = [ [ $emptyValue ] ];
        }

        return $retval;
    }

    public function provideArraysWithContentToTest()
    {
        $valuesWithContent = $this->provideValuesWithContentToTest();

        $retval = [];
        foreach ($valuesWithContent as $value) {
            $retval[] = [ [ $value[0] ] ];
        }

        return $retval;
    }

    public function provideIteratorsWithNoContentToTest()
    {
        return [
            [ new ArrayObject() ],
            [ new stdClass ],
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
            $retval[] = [ new ArrayObject([$emptyValue[0]]) ];
            $retval[] = [ (object)[[$emptyValue[0]]] ];
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
            $retval[] = [ new ArrayObject([$value[0]]) ];
            $retval[] = [ (object)[ 'key' => $value[0]] ];
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
            [ null ],
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
            [ '' ],
        ];
    }

    public function provideStringsWithWhitespaceToTest()
    {
        return [
            [ ' ' ],
            [ "\t" ],
            [ "\n" ],
            [ "\r" ],
            [ "\r\n" ],
        ];
    }

    public function provideStringsWithContentToTest()
    {
        return [
            [ "hello, world!" ],
        ];
    }

    public function provideEverythingElseToTest()
    {
        return [
            [ [ true ] ],
            [ [ false ] ],
            [ [ self::class, 'provideDataToTest'] ],
            [ true ],
            [ false ],
            [ function(){} ],
            [ 3.1415927 ],
            [ 100 ],
            [ 0 ],
            [ new RequireNotEmpty  ],
            [ STDIN ],
        ];
    }
}
