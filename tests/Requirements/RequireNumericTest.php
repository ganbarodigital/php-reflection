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
 * @coversDefaultClass GanbaroDigital\Reflection\Requirements\RequireNumeric
 */
class RequireNumericTest extends PHPUnit_Framework_TestCase
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

        $obj = new RequireNumeric;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof RequireNumeric);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideNumerics
     */
    public function testCanUseAsObject($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new RequireNumeric;

        // ----------------------------------------------------------------
        // perform the change

        $obj($item);
    }

    /**
     * @covers ::check
     * @covers ::checkMixed
     * @dataProvider provideNumerics
     */
    public function testCanCallStatically($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        RequireNumeric::check($item);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideNonNumerics
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     */
    public function testRejectsNonNumericsWhenUsedAsObject($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new RequireNumeric;

        // ----------------------------------------------------------------
        // perform the change

        $obj($item);
    }

    /**
     * @covers ::check
     * @covers ::checkMixed
     * @dataProvider provideNonNumerics
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     */
    public function testRejectsNonNumericsWhenCalledStatically($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        RequireNumeric::check($item);
    }

    public function provideNumerics()
    {
        return [
            [ 100 ],
            [ 0 ],
            [ 3.1415927 ],
            [ "42" ],
            [ 0x539 ],
            [ 02471 ],
            [ 0b10100111001 ],
            [ 1337e0 ],
        ];
    }

    public function provideNonNumerics()
    {
        return [
            [ null ],
            [ false ],
            [ true ],
            [ [ ] ],
            [ new stdClass ],
            [ "hello, world!" ],
            [ fopen("php://input", "r") ],
        ];
    }}