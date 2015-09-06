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

use PHPUnit_Framework_TestCase;
use stdClass;

class IsDefinedInterfaceTest_Class1 { }
interface IsDefinedInterface__Interface1 { }
trait IsDefinedInterface_Trait1 { }

/**
 * @coversDefaultClass GanbaroDigital\Reflection\Checks\IsDefinedInterface
 */
class IsDefinedInterfaceTest extends PHPUnit_Framework_TestCase
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

        $obj = new IsDefinedInterface();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof IsDefinedInterface);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideDataToTest
     */
    public function testCanUseAsObject($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsDefinedInterface;

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

        $actualResult = IsDefinedInterface::check($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::__invoke
     * @covers ::check
     * @dataProvider provideValidInterfacesToTest
     */
    public function testReturnsTrueForInterfaceNames($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsDefinedInterface;
        $this->assertTrue(interface_exists($data));

        // ----------------------------------------------------------------
        // perform the change

        $actualResult1 = $obj($data);
        $actualResult2 = IsDefinedInterface::check($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult1);
        $this->assertTrue($actualResult2);
    }

    /**
     * @covers ::__invoke
     * @covers ::check
     * @dataProvider provideValidClassesToTest
     */
    public function testReturnsFalseForClassNames($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsDefinedInterface;
        $this->assertTrue(class_exists($data));

        // ----------------------------------------------------------------
        // perform the change

        $actualResult1 = $obj($data);
        $actualResult2 = IsDefinedInterface::check($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actualResult1);
        $this->assertFalse($actualResult2);
    }

    /**
     * @covers ::__invoke
     * @covers ::check
     * @dataProvider provideValidTraitsToTest
     */
    public function testReturnsFalseForTraitNames($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsDefinedInterface;
        $this->assertTrue(trait_exists($data));

        // ----------------------------------------------------------------
        // perform the change

        $actualResult1 = $obj($data);
        $actualResult2 = IsDefinedInterface::check($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actualResult1);
        $this->assertFalse($actualResult2);
    }

    /**
     * @covers ::__invoke
     * @covers ::check
     * @dataProvider provideScalarsToTest
     */
    public function testReturnsFalseForEverythingElse($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsDefinedInterface;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult1 = $obj($data);
        $actualResult2 = IsDefinedInterface::check($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actualResult1);
        $this->assertFalse($actualResult2);
    }


    public function provideDataToTest()
    {
        return array_merge(
            $this->provideValidClassesToTest(),
            $this->provideValidInterfacesToTest(),
            $this->provideValidTraitsToTest(),
            $this->provideScalarsToTest()
        );
    }

    public function provideValidClassesToTest()
    {
        return [
            [ IsDefinedInterfaceTest_Class1::class, false ]
        ];
    }

    public function provideValidInterfacesToTest()
    {
        return [
            [ IsDefinedInterface__Interface1::class, true ],
        ];
    }

    public function provideValidTraitsToTest()
    {
        return [
            [ IsDefinedInterface_Trait1::class, false ],
        ];
    }

    public function provideScalarsToTest()
    {
        return [
            [ null, false ],
            [ [ IsDefinedInterface::class ], false ],
            [ true, false ],
            [ false, false ],
            [ 3.1415927, false ],
            [ 0, false ],
            [ 100, false ],
            [ new IsDefinedInterface, false ],
            [ "hello, world", false ],
        ];
    }
}