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

interface IsCompatibleWith_Interface1 { }
class IsCompatibleWithTest_Class1 { }
class IsCompatibleWithTest_Class2 implements IsCompatibleWith_Interface1 { }
class IsCompatibleWithTest_Class3 extends IsCompatibleWithTest_Class1 { }
class IsCompatibleWithTest_Class4 extends IsCompatibleWithTest_Class2 { }
trait IsCompatibleWith_Trait1 { }

/**
 * @coversDefaultClass GanbaroDigital\Reflection\Checks\IsCompatibleWith
 */
class IsCompatibleWithTest extends PHPUnit_Framework_TestCase
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

        $obj = new IsCompatibleWith();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof IsCompatibleWith);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideDataToTest
     */
    public function testCanUseAsObject($data, $constraint, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsCompatibleWith;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data, $constraint);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::check
     * @dataProvider provideDataToTest
     */
    public function testCanCallStatically($data, $constraint, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = IsCompatibleWith::check($data, $constraint);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::__invoke
     * @covers ::check
     * @covers ::checkString
     * @dataProvider provideClassNamesCompatibleWithClassNamesToTest
     */
    public function testReturnsTrueForClassNamesCompatibleWithClassNames($data, $constraint, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsCompatibleWith;
        $this->assertTrue(class_exists($data));
        $this->assertTrue(class_exists($constraint) || interface_exists($constraint));

        // ----------------------------------------------------------------
        // perform the change

        $actualResult1 = $obj($data, $constraint);
        $actualResult2 = IsCompatibleWith::check($data, $constraint);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult1);
        $this->assertTrue($actualResult2);
    }

    /**
     * @covers ::__invoke
     * @covers ::check
     * @covers ::checkString
     * @dataProvider provideClassNamesCompatibleWithObjectsToTest
     */
    public function testReturnsTrueForClassNamesCompatibleWithObjects($data, $constraint, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsCompatibleWith;
        $this->assertTrue(class_exists($data));
        $this->assertTrue(is_object($constraint));

        // ----------------------------------------------------------------
        // perform the change

        $actualResult1 = $obj($data, $constraint);
        $actualResult2 = IsCompatibleWith::check($data, $constraint);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult1);
        $this->assertTrue($actualResult2);
    }

    /**
     * @covers ::__invoke
     * @covers ::check
     * @covers ::checkObject
     * @dataProvider provideObjectsCompatibleWithObjectsToTest
     */
    public function testReturnsTrueForObjectsCompatibleWithObjects($data, $constraint, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsCompatibleWith;
        $this->assertTrue(is_object($data));
        $this->assertTrue(is_object($constraint));

        // ----------------------------------------------------------------
        // perform the change

        $actualResult1 = $obj($data, $constraint);
        $actualResult2 = IsCompatibleWith::check($data, $constraint);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult1);
        $this->assertTrue($actualResult2);
    }

    /**
     * @covers ::__invoke
     * @covers ::check
     * @covers ::checkObject
     * @dataProvider provideObjectsCompatibleWithClassNamesToTest
     */
    public function testReturnsTrueForObjectsCompatibleWithClassNames($data, $constraint, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsCompatibleWith;
        $this->assertTrue(is_object($data));
        $this->assertTrue(class_exists($constraint) || interface_exists($constraint));

        // ----------------------------------------------------------------
        // perform the change

        $actualResult1 = $obj($data, $constraint);
        $actualResult2 = IsCompatibleWith::check($data, $constraint);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult1);
        $this->assertTrue($actualResult2);
    }

    /**
     * @covers ::__invoke
     * @covers ::check
     * @covers ::checkObject
     * @covers ::checkString
     * @dataProvider provideIncompatibleClassNamesToTest
     */
    public function testReturnsFalseForIncompatibleClassNames($data, $constraint, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsCompatibleWith;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult1 = $obj($data, $constraint);
        $actualResult2 = IsCompatibleWith::check($data, $constraint);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actualResult1);
        $this->assertFalse($actualResult2);
    }

    /**
     * @covers ::nothingMatchesTheInputType
     * @dataProvider provideBadInputs
     * @expectedException GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType
     */
    public function testRejectsEverythingElse($data, $constraint)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        IsCompatibleWith::check($data, $constraint);
    }


    public function provideDataToTest()
    {
        return array_merge(
            $this->provideClassNamesCompatibleWithClassNamesToTest(),
            $this->provideClassNamesCompatibleWithObjectsToTest(),
            $this->provideObjectsCompatibleWithObjectsToTest(),
            $this->provideObjectsCompatibleWithClassNamesToTest(),
            $this->provideIncompatibleClassNamesToTest()
        );
    }

    public function provideClassNamesCompatibleWithClassNamesToTest()
    {
        return [
            [ IsCompatibleWithTest_Class3::class, IsCompatibleWithTest_Class1::class, true ],
            [ IsCompatibleWithTest_Class4::class, IsCompatibleWithTest_Class2::class, true ],
            [ IsCompatibleWithTest_Class4::class, IsCompatibleWith_Interface1::class, true ],
        ];
    }

    public function provideClassNamesCompatibleWithObjectsToTest()
    {
        return [
            [ IsCompatibleWithTest_Class3::class, new IsCompatibleWithTest_Class1, true ],
            [ IsCompatibleWithTest_Class4::class, new IsCompatibleWithTest_Class2, true ],
        ];
    }

    public function provideObjectsCompatibleWithObjectsToTest()
    {
        return [
            [ new IsCompatibleWithTest_Class3, new IsCompatibleWithTest_Class1, true ],
            [ new IsCompatibleWithTest_Class4, new IsCompatibleWithTest_Class2, true ],
        ];
    }

    public function provideObjectsCompatibleWithClassNamesToTest()
    {
        return [
            [ new IsCompatibleWithTest_Class3, IsCompatibleWithTest_Class1::class, true ],
            [ new IsCompatibleWithTest_Class4, IsCompatibleWithTest_Class2::class, true ],
            [ new IsCompatibleWithTest_Class4, IsCompatibleWith_Interface1::class, true ]
        ];
    }

    public function provideIncompatibleClassNamesToTest()
    {
        return [
            [ IsCompatibleWithTest_Class4::class, IsCompatibleWithTest_Class1::class, false ],
            [ IsCompatibleWithTest_Class3::class, IsCompatibleWithTest_Class2::class, false ],
        ];
    }

    public function provideBadInputs()
    {
        return [
            [ null, false ],
            [ [ IsCompatibleWith::class ], false ],
            [ true, false ],
            [ false, false ],
            [ 3.1415927, false ],
            [ 0, false ],
            [ 100, false ],
            [ new IsCompatibleWith, false ],
            [ "hello, world", false ],
        ];
    }
}