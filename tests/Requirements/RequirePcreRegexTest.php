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

class RequirePcreRegexTest_Target1
{
    public function __toString()
    {
        return "hello, world!";
    }
}

/**
 * @coversDefaultClass GanbaroDigital\Reflection\Requirements\RequirePcreRegex
 */
class RequirePcreRegexTest extends PHPUnit_Framework_TestCase
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

        $obj = new RequirePcreRegex;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof RequirePcreRegex);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideValidRegexesToCheck
     */
    public function testCanUseAsObject($item, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new RequirePcreRegex;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($item);
    }

    /**
     * @covers ::check
     * @covers ::checkString
     * @dataProvider provideValidRegexesToCheck
     */
    public function testCanCallStatically($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        RequirePcreRegex::check($item);
    }

    /**
     * @covers ::__invoke
     * @covers ::checkString
     * @dataProvider provideInvalidRegexesToCheck
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     */
    public function testRejectsInvalidRegexesWhenUsedAsObject($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new RequirePcreRegex;

        // ----------------------------------------------------------------
        // perform the change

        $obj($item);
    }

    /**
     * @covers ::check
     * @covers ::checkString
     * @dataProvider provideInvalidRegexesToCheck
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     */
    public function testRejectsInvalidRegexesWhenCalledStatically($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        RequirePcreRegex::check($item);
    }

    public function provideStringys()
    {
        return [
            [ "hello, world!" ],
            [ new RequireStringTest_Target1 ]
        ];
    }

    public function provideNonStringys()
    {
        return [
            [ null ],
            [ [] ],
            [ false ],
            [ true ],
            [ 3.1415927 ],
            [ 100 ],
            [ new stdClass ],
            [ fopen("php://input", "r") ],
        ];
    }

    public function provideDataToCheck()
    {
        return array_merge(
            $this->provideValidRegexesToCheck(),
            $this->provideInvalidRegexesToCheck()
        );
    }

    public function provideValidRegexesToCheck()
    {
        return [
            [ "/hello/", true ],
        ];
    }

    public function provideInvalidRegexesToCheck()
    {
        return [
            [ "/hello", false ],
        ];
    }
}