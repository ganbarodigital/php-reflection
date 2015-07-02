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
 * @package   Reflection/Filters
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-file-system
 */

namespace GanbaroDigital\Reflection\Filters;

use PHPUnit_Framework_TestCase;

class FilterMethodNamesTest_Target1
{
    public function objectMethod1() { }
    protected function objectMethod2() { }
    private function objectMethod3() { }
    public static function staticMethod1() { }
    protected static function staticMethod2() { }
    private static function staticMethod3() { }
}

/**
 * @coversDefaultClass GanbaroDigital\Reflection\Filters\FilterMethodNames
 */
class FilterMethodNamesTest extends PHPUnit_Framework_TestCase
{

    /**
     * @coversNone
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $obj = new FilterMethodNames;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof FilterMethodNames);
    }

    /**
     * @covers ::__invoke
     * @covers ::fromMixed
     * @covers ::fromString
     * @covers ::fromClassName
     * @covers ::fromObject
     * @dataProvider provideTargetsToFilter
     */
    public function testCanUseAsObject($target, $expectedMethods)
    {
        // ----------------------------------------------------------------
        // setup your test

        $filter = new FilterMethodNames;

        // ----------------------------------------------------------------
        // perform the change

        $actualMethods = $filter($target);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedMethods, $actualMethods);
    }

    public function provideTargetsToFilter()
    {
        return [
            [ FilterMethodNamesTest_Target1::class, [ 'staticMethod1' => 'staticMethod1' ] ],
            [ new FilterMethodNamesTest_Target1, [ 'objectMethod1' => 'objectMethod1' ] ],
        ];
    }

    /**
     * @covers ::fromString
     * @covers ::fromClassName
     */
    public function testCanGetMethodsFromClass()
    {
        // ----------------------------------------------------------------
        // setup your test

        $target = new FilterMethodNamesTest_Target1;
        $expectedMethods = [
            'staticMethod1' => 'staticMethod1'
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualMethods = FilterMethodNames::fromString(get_class($target));

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedMethods, $actualMethods);
    }

    /**
     * @covers ::fromObject
     */
    public function testCanGetMethodsFromObject()
    {
        // ----------------------------------------------------------------
        // setup your test

        $target = new FilterMethodNamesTest_Target1;
        $expectedMethods = [
            'objectMethod1' => 'objectMethod1'
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualMethods = FilterMethodNames::fromObject($target);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedMethods, $actualMethods);
    }

    /**
     * @covers ::__invoke
     * @covers ::fromMixed
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     *
     * @dataProvider provideBadDataToTest
     */
    public function testRejectsOtherDataTypesWhenUsedAsObject($target)
    {
        // ----------------------------------------------------------------
        // setup your test

        $filter = new FilterMethodNames;

        // ----------------------------------------------------------------
        // perform the change

        $filter($target);
    }

    /**
     * @covers ::fromMixed
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

        FilterMethodNames::fromMixed($target);
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

        $filter = new FilterMethodNamesTest;

        // ----------------------------------------------------------------
        // perform the change

        FilterMethodNames::fromString($target);
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

        $filter = new FilterMethodNamesTest;

        // ----------------------------------------------------------------
        // perform the change

        FilterMethodNames::fromObject($target);
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

        FilterMethodNames::fromString('NoSuch\\Class\\Exists\\At\\All');
    }

}