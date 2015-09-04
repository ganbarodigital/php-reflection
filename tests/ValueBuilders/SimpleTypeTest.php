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
 * @package   Reflection/ValueBuilders
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-file-system
 */

namespace GanbaroDigital\Reflection\ValueBuilders;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass GanbaroDigital\Reflection\ValueBuilders\SimpleType
 */
class SimpleTypeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @coversNone
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // perform the change

        $obj = new SimpleType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof SimpleType);
    }

    /**
     * @covers ::__invoke
     * @covers ::from
     * @covers ::fromObject
     * @dataProvider provideDataToTest
     */
    public function testCanUseAsObject($data, $expectedType)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new SimpleType();

        // ----------------------------------------------------------------
        // perform the change

        $actualType = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedType, $actualType);
    }

    public function provideDataToTest()
    {
        return [
            [ null, 'NULL' ],
            [ [ 1,2,3 ], 'array' ],
            [ true, 'boolean' ],
            [ false, 'boolean' ],
            [ 0.0, 'double' ],
            [ 0, 'integer' ],
            [ 1, 'integer' ],
            [ new SimpleType(), SimpleType::class ],
            [ [ SimpleType::class, 'from' ], 'callable' ],
            [ '100', 'string' ],
        ];
    }

    /**
     * @covers ::__invoke
     * @covers ::from
     * @covers ::fromObject
     * @dataProvider provideDataToTest
     */
    public function testCanStaticallyGetAnyType($data, $expectedType)
    {
        // ----------------------------------------------------------------
        // setup your test


        // ----------------------------------------------------------------
        // perform the change

        $actualType = SimpleType::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedType, $actualType);
    }

    /**
     * @covers ::fromObject
     */
    public function testCanStaticallyGetObjectType()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = new SimpleType();
        $expectedType = SimpleType::class;

        // ----------------------------------------------------------------
        // perform the change

        $actualType = SimpleType::fromObject($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedType, $actualType);
    }

}