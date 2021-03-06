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

class RequireDefinedClassTest_Class1 { }
interface RequireDefinedClass_Interface1 { }
trait RequireDefinedClass_Trait1 { }

/**
 * @coversDefaultClass GanbaroDigital\Reflection\Requirements\RequireDefinedClass
 */
class RequireDefinedClassTest extends PHPUnit_Framework_TestCase
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

        $obj = new RequireDefinedClass;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof RequireDefinedClass);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideClasses
     */
    public function testCanUseAsObject($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new RequireDefinedClass;

        // ----------------------------------------------------------------
        // perform the change

        $obj($item);
    }

    /**
     * @covers ::check
     * @dataProvider provideClasses
     */
    public function testCanCallStatically($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        RequireDefinedClass::check($item);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideInterfaces
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     */
    public function testRejectsInterfacesWhenUsedAsObject($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new RequireDefinedClass;

        // ----------------------------------------------------------------
        // perform the change

        $obj($item);
    }

    /**
     * @covers ::check
     * @dataProvider provideInterfaces
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     */
    public function testRejectsInterfacesWhenCalledStatically($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        RequireDefinedClass::check($item);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideTraits
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     */
    public function testRejectsTraitsWhenUsedAsObject($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new RequireDefinedClass;

        // ----------------------------------------------------------------
        // perform the change

        $obj($item);
    }

    /**
     * @covers ::check
     * @dataProvider provideTraits
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     */
    public function testTraitsInterfacesWhenCalledStatically($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        RequireDefinedClass::check($item);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideScalars
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     */
    public function testRejectsEverythingElseWhenUsedAsObject($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new RequireDefinedClass;

        // ----------------------------------------------------------------
        // perform the change

        $obj($item);
    }

    /**
     * @covers ::check
     * @dataProvider provideScalars
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     */
    public function testRejectsEverythingElseWhenCalledStatically($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        RequireDefinedClass::check($item);
    }

    public function provideClasses()
    {
        return [
            [ RequireDefinedClassTest_Class1::class, true ]
        ];
    }

    public function provideInterfaces()
    {
        return [
            [ RequireDefinedClass_Interface1::class, false ],
        ];
    }

    public function provideTraits()
    {
        return [
            [ RequireDefinedClass_Trait1::class, false ],
        ];
    }

    public function provideScalars()
    {
        return [
            [ null, false ],
            [ [ IsDefinedClass::class ], false ],
            [ true, false ],
            [ false, false ],
            [ 3.1415927, false ],
            [ 0, false ],
            [ 100, false ],
            [ new IsDefinedClass, false ],
            [ "hello, world", false ],
        ];
    }

}