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

use GanbaroDigital\Defensive\Requirements\RequireAnyOneOf;
use GanbaroDigital\Reflection\Checks\IsObject;
use GanbaroDigital\Reflection\Checks\IsStringy;
use GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType;
use GanbaroDigital\Reflection\Requirements\RequireObject;
use GanbaroDigital\Reflection\Requirements\RequireStringy;
use GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList;
use GanbaroDigital\Reflection\ValueBuilders\LookupMethodByType;
use GanbaroDigital\Reflection\ValueBuilders\SimpleType;

class IsCompatibleWith
{
    use LookupMethodByType;

    /**
     * is $data compatible with $constraint?
     *
     * @param  mixed $data
     *         the object to check
     * @param  string|object $constraint
     * @return boolean
     *         TRUE if $data is compatible
     *         FALSE otherwise
     */
    public function __invoke($data, $constraint)
    {
        return self::check($data, $constraint);
    }

    /**
     * is $data compatible with $constraint?
     *
     * @param  mixed $data
     *         the object to check
     * @param  string|object $constraint
     * @return boolean
     *         TRUE if $data is compatible
     *         FALSE otherwise
     */
    public static function check($data, $constraint)
    {
        $method = self::lookupMethodFor($data, self::$dispatchTable);
        return self::$method($data, $constraint);
    }

    /**
     * is $data compatible with $constraint?
     *
     * @param  object $data
     *         the object to check
     * @param  string|object $constraint
     *         the class or object that $data must be compatible with
     * @return boolean
     *         TRUE if $data is compatible
     *         FALSE otherwise
     */
    public static function checkObject($data, $constraint)
    {
        // defensive programming!
        RequireObject::check($data, E4xx_UnsupportedType::class);
        RequireAnyOneOf::check([ new IsObject, new IsStringy], [$constraint], E4xx_UnsupportedType::class);

        // this is the easiest test case of all :)
        return $data instanceof $constraint;
    }

    /**
     * is $data compatible with $constraint?
     *
     * @param  string $data
     *         the class name to check
     * @param  string|object $constraint
     *         the class or object that $data must be compatible with
     * @return boolean
     *         TRUE if $data is compatible
     *         FALSE otherwise
     */
    public static function checkString($data, $constraint)
    {
        // defensive programming!
        RequireStringy::check($data, E4xx_UnsupportedType::class);
        RequireAnyOneOf::check([ new IsObject, new IsStringy], [$constraint], E4xx_UnsupportedType::class);

        $compatibleTypes = AllMatchingTypesList::fromClass($data);
        if (is_object($constraint)) {
            $constraint = get_class($constraint);
        }

        // is our constraint in the list of data types that $data can be?
        if (in_array($constraint, $compatibleTypes)) {
            return true;
        }

        // if we get here, we have run out of ideas
        return false;
    }

    /**
     * called when $data is a data type that we do not support
     *
     * @param  mixed $data
     * @param  mixed $constraint
     * @return void
     */
    public static function nothingMatchesTheInputType($data, $constraint)
    {
        throw new E4xx_UnsupportedType(SimpleType::from($data));
    }

    /**
     * our lookup table of which method to call for which supported data type
     * @var array
     */
    private static $dispatchTable = [
        'Object' => 'checkObject',
        'String' => 'checkString',
    ];
}