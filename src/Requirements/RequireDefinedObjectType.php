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

use GanbaroDigital\Reflection\Exceptions\E4xx_NoSuchClass;
use GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType;
use GanbaroDigital\Reflection\Checks\IsDefinedObjectType;
use GanbaroDigital\Reflection\ValueBuilders\SimpleType;

class RequireDefinedObjectType
{
    /**
     * throws exceptions if $item is not a PHP class or interface that exists
     *
     * this is a wrapper around our IsDefinedObjectType check
     *
     * @param  mixed $item
     *         the container to check
     * @param  string $eNoSuchClass
     *         the exception to throw if $item isn't a valid PHP class
     * @param  string $eUnsupportedType
     *         the exception to throw if $item isn't something that we can check
     * @return void
     */
    public static function check($item, $eNoSuchClass = E4xx_NoSuchClass::class, $eUnsupportedType = E4xx_UnsupportedType::class)
    {
        RequireStringy::check($item, $eUnsupportedType);

        if (trait_exists($item)) {
            throw new $eUnsupportedType(SimpleType::from($item));
        }

        // make sure we have a PHP class that exists
        if (!IsDefinedObjectType::check($item)) {
            throw new $eNoSuchClass($item);
        }
    }

    /**
     * throws exceptions if $item is not a PHP class or interface that exists
     *
     * this is a wrapper around our IsDefinedObjectType check
     *
     * @param  mixed $item
     *         the container to check
     * @param  string $eNoSuchClass
     *         the exception to throw if $item isn't a valid PHP class
     * @param  string $eUnsupportedType
     *         the exception to throw if $item isn't something that we can check
     * @return void
     */
    public function __invoke($item, $eNoSuchClass = E4xx_NoSuchClass::class, $eUnsupportedType = E4xx_UnsupportedType::class)
    {
        self::check($item, $eNoSuchClass, $eUnsupportedType);
    }
}