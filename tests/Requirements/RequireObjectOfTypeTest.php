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

use GanbaroDigital\Reflection\Checks\IsDefinedClass;
use PHPUnit_Framework_TestCase;
use stdClass;

class RequireObjectOfTypeTest_Class1 { }
interface RequireObjectOfType_Interface1 { }
trait RequireObjectOfType_Trait1 { }

/**
 * @coversDefaultClass GanbaroDigital\Reflection\Requirements\RequireObjectOfType
 */
class RequireObjectOfTypeTest extends PHPUnit_Framework_TestCase
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

        $obj = new RequireObjectOfType;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof RequireObjectOfType);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideObjects
     */
    public function testCanUseAsObject($item, $type)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new RequireObjectOfType;

        // ----------------------------------------------------------------
        // perform the change

        $obj($item, $type);
    }

    /**
     * @covers ::check
     * @dataProvider provideObjects
     */
    public function testCanCallStatically($item, $type)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        RequireObjectOfType::check($item, $type);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideScalars
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     */
    public function testRejectsEverythingElseWhenUsedAsObject($item, $type)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new RequireObjectOfType;

        // ----------------------------------------------------------------
        // perform the change

        $obj($item, $type);
    }

    /**
     * @covers ::check
     * @dataProvider provideScalars
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     */
    public function testRejectsEverythingElseWhenCalledStatically($item, $type)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        RequireObjectOfType::check($item, $type);
    }

    public function provideObjects()
    {
        return [
            [ new RequireObjectOfTypeTest_Class1, RequireObjectOfTypeTest_Class1::class ],
            [ new stdClass, stdClass::class ],
        ];
    }

    public function provideScalars()
    {
        return [
            [ null, stdClass::class ],
            [ [ IsDefinedClass::class ], stdClass::class ],
            [ true, stdClass::class ],
            [ false, stdClass::class ],
            [ 3.1415927, stdClass::class ],
            [ 0, stdClass::class ],
            [ 100, stdClass::class ],
            [ "hello, world", stdClass::class ],
        ];
    }

}