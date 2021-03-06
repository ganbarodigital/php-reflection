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

use ArrayIterator;
use ArrayObject;
use GanbaroDigital\UnitTestHelpers\ClassesAndObjects\InvokeMethod;

use IteratorAggregate;
use PHPUnit_Framework_TestCase;
use stdClass;

// cribbed directly from the PHP manual
class IsArrayTest_Target1 implements IteratorAggregate
{
    public function getIterator()
    {
        return new ArrayIterator([]);
    }
}

/**
 * @coversDefaultClass GanbaroDigital\Reflection\Checks\IsArray
 */
class IsArrayTest extends PHPUnit_Framework_TestCase
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

        $obj = new IsArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof IsArray);
    }

    /**
     * @covers ::__invoke
     * @covers ::check
     */
    public function testReturnsTrueForArray()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsArray();
        $data = [];
        $expectedResult = true;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::__invoke
     * @covers ::check
     */
    public function testReturnsFalseForIteratorAggregate()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsArray();
        $data = new IsArrayTest_Target1;
        $expectedResult = false;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::__invoke
     * @covers ::check
     */
    public function testReturnsFalseForArrayIterator()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsArray();
        $data = new ArrayIterator([]);
        $expectedResult = false;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::__invoke
     * @covers ::check
     */
    public function testReturnsFalseForArrayObject()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsArray();
        $data = new ArrayObject;
        $expectedResult = false;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::__invoke
     * @covers ::check
     */
    public function testReturnsFalseForStdclass()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsArray();
        $data = new stdClass;
        $expectedResult = false;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::__invoke
     * @covers ::check
     * @dataProvider provideEverythingElse
     */
    public function testReturnsFalseForEverythingElse($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsArray();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($item);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actualResult);
    }

    public function provideEverythingElse()
    {
        return [
            [ null ],
            [ true ],
            [ false ],
            [ 3.1415927 ],
            [ 100 ],
            [ "hello world"],
            [ new IsArray ],
        ];
    }
}