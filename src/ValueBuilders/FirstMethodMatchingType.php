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

use GanbaroDigital\DataContainers\Caches\StaticDataCache;
use GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType;
use GanbaroDigital\Reflection\Filters\FilterMethodNames;

final class FirstMethodMatchingType
{
    use StaticDataCache;

    /**
     * find a method on a class / object that can accept a given piece of data
     *
     * @param  mixed $data
     *         the data we are looking for
     * @param  string|object $target
     *         the class or object that we want to call with $data
     * @param  string $methodPrefix
     *         the prefix to put on the front of the method name
     * @param  string $eUnsupportedType
     *         the exception to throw if no matching method found
     * @return string
     *         the first method on $target that will accept $data
     */
    public static function fromMixed($data, $target, $methodPrefix, $eUnsupportedType = E4xx_UnsupportedType::class)
    {
        // do we have this in the cache?
        if (($retval = self::getMethodFromCache($data, $target)) !== null) {
            return $retval;
        }

        // what method do we want to call?
        $retval = self::findMethodToCall($data, $target, $methodPrefix, $eUnsupportedType);

        // cache it for next time
        self::setMethodInCache($data, $target, $retval);

        // all done
        return $retval;
    }

    /**
     * what key should we use to look up information on $data in our cache?
     *
     * @param  mixed $data
     *         the data to examine
     * @param  string|object $target
     *         the target we want to call
     * @return string
     *         the cache key to use
     */
    private static function getCacheName($data, $target)
    {
        // our cache key to be
        if (is_string($target)) {
            $retval = $target;
            $suffix = 'class';
        }
        else {
            $retval = get_class($target);
            $suffix = 'object';
        }

        if (is_object($data)) {
            $retval .= '::' . get_class($data);
        }
        else {
            $retval .= '::' . gettype($data);
        }

        return $retval . '::' . $suffix;
    }

    /**
     * do we have an appropriate method in our cache?
     *
     * @param  mixed $data
     *         the data we want to call $target with
     * @param  string|object $target
     *         the target that we want to call
     * @return string|null
     *         the cached method name, or NULL
     */
    private static function getMethodFromCache($data, $target)
    {
        $cacheKey = self::getCacheName($data, $target);
        return self::getFromCache($cacheKey);
    }

    /**
     * remember the method to call for next time
     *
     * @param mixed $data
     *        the data we want to call $target with
     * @param string|object $target
     *        the target that we want to call
     * @param string $methodName
     *        the method name that we want to remember
     */
    private static function setMethodInCache($data, $target, $methodName)
    {
        $cacheKey = self::getCacheName($data, $target);
        self::setInCache($cacheKey, $methodName);
    }

    /**
     * which method should we call on $target for $data?
     *
     * @param  mixed $data
     *         the data we want to call $target with
     * @param  string|object $target
     *         the target that we want to call
     * @param  string $methodPrefix
     *         the prefix at the front of methods on $target
     * @param  string $eUnsupportedType
     *         the exception to throw if no matching method found
     * @return string
     *         the method that suits $data the best
     *
     * @throws E4xx_UnsupportedType
     */
    private static function findMethodToCall($data, $target, $methodPrefix, $eUnsupportedType = E4xx_UnsupportedType::class)
    {
        // is there a method that matches the data type?
        if (($retval = self::findFirstMatchingMethod($data, $target, $methodPrefix)) !== null) {
            return $retval;
        }

        // before we give in ...
        //
        // PHP does not allow static and non-static methods with the same
        // name, so it might be that the only available method on an
        // object is the __invoke magic method
        if (is_object($target) && method_exists($target, '__invoke')) {
            return '__invoke';
        }

        // no match
        //
        // treat as an error
        throw new $eUnsupportedType(gettype($data));
    }

    /**
     * find the first method on $target that matches $data's data type
     *
     * @param  mixed $data
     *         the data we want to call $target with
     * @param  string|object $target
     *         the target that we want to call
     * @param  string $methodPrefix
     *         the prefix at the front of methods on $target
     * @return string|null
     *         the method that suits $data the best
     */
    private static function findFirstMatchingMethod($data, $target, $methodPrefix)
    {
        // no, so we need to build it
        $possibleTypes   = AllMatchingTypesList::fromMixed($data);
        $possibleMethods = CallableMethodsList::fromMixed($target);

        foreach ($possibleTypes as $possibleType) {
            $targetMethodName = $methodPrefix . $possibleType;
            if (isset($possibleMethods[$targetMethodName])) {
                // all done
                return $targetMethodName;
            }
        }

        // no match
        return null;
    }

    /**
     * find a method on a class / object that can accept a given piece of data
     *
     * @param  mixed $data
     *         the data we are looking for
     * @param  string|object $target
     *         the class or object that we want to call with $data
     * @param  string $methodPrefix
     *         the prefix to put on the front of the method name
     * @param  string $eUnsupportedType
     *         the exception to throw if no matching method found
     * @return string
     *         the first method on $target that will accept $data
     */
    public function __invoke($data, $target, $methodPrefix, $eUnsupportedType = E4xx_UnsupportedType::class)
    {
        return self::fromMixed($data, $target, $methodPrefix, $eUnsupportedType);
    }
}