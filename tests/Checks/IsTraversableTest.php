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
class IsTraversableTest_Target1 implements IteratorAggregate
{
    public function getIterator()
    {
        return new ArrayIterator([]);
    }
}

/**
 * @coversDefaultClass GanbaroDigital\Reflection\Checks\IsTraversable
 */
class IsTraversableTest extends PHPUnit_Framework_TestCase
{
    public function setup()
    {
        // make sure the cache is always empty before a test runs
        InvokeMethod::onClass(IsTraversable::class, 'resetCache');
    }

    /**
     * @coversNothing
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $obj = new IsTraversable();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof IsTraversable);
    }

    /**
     * @covers ::check
     * @covers ::checkMixed
     * @covers ::calculateResult
     * @covers ::__invoke
     */
    public function testCanCheckForArray()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsTraversable();
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
     * @covers ::check
     * @covers ::checkMixed
     * @covers ::calculateResult
     * @covers ::__invoke
     */
    public function testCanCheckForIteratorAggregate()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsTraversable();
        $data = new IsTraversableTest_Target1;
        $expectedResult = true;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::check
     * @covers ::checkMixed
     * @covers ::calculateResult
     * @covers ::__invoke
     */
    public function testCanCheckForArrayIterator()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsTraversable();
        $data = new ArrayIterator([]);
        $expectedResult = true;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::check
     * @covers ::checkMixed
     * @covers ::calculateResult
     * @covers ::__invoke
     */
    public function testCanCheckForArrayObject()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsTraversable();
        $data = new ArrayObject;
        $expectedResult = true;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::check
     * @covers ::checkMixed
     * @covers ::calculateResult
     * @covers ::__invoke
     */
    public function testCanCheckForStdclass()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsTraversable();
        $data = new stdClass;
        $expectedResult = true;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::check
     * @covers ::__invoke
     * @covers ::checkMixed
     * @covers ::calculateResult
     * @dataProvider provideNonTraversables
     */
    public function testRejectsEverythingElse($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsTraversable();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($item);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actualResult);
    }

    public function provideNonTraversables()
    {
        return [
            [ null ],
            [ true ],
            [ false ],
            [ 3.1415927 ],
            [ 100 ],
            [ "hello world"],
            [ new IsTraversable ],
        ];
    }

    /**
     * @covers ::setCachedResult
     * @covers ::getCacheKey
     */
    public function testWritesToStaticCache()
    {
        // ----------------------------------------------------------------
        // setup your test

        // prove that this result is not in the cache
        $expectedResult = false;
        $data = 3.1415927;
        $this->assertEquals(null, InvokeMethod::onClass(IsTraversable::class, 'getCachedResult', [ $data ]));

        // ----------------------------------------------------------------
        // perform the change

        IsTraversable::check($data);

        // ----------------------------------------------------------------
        // test the results

        $actualResult = InvokeMethod::onClass(IsTraversable::class, 'getCachedResult', [ $data ]);
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::getCachedResult
     * @covers ::getCacheKey
     * @covers ::check
     * @covers ::checkMixed
     */
    public function testReadsFromStaticCache()
    {
        // ----------------------------------------------------------------
        // setup your test

        // force a nonsense result into the cache
        $expectedResult = 100;
        $data = 3.1415927;
        InvokeMethod::onClass(IsTraversable::class, 'setCachedResult', [ $data, $expectedResult ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = IsTraversable::check($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::getCacheKey
     */
    public function testUsesClassNameForObjectKeyIntoStaticCache()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = new stdClass;
        $expectedResult = get_class($data);

        // ----------------------------------------------------------------
        // perform the change

        // ----------------------------------------------------------------
        // test the results

        $actualResult = InvokeMethod::onClass(IsTraversable::class, 'getCacheKey', [$data]);
        $this->assertEquals($expectedResult, $actualResult);
    }
}