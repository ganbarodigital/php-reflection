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

use ReflectionClass;
use ReflectionObject;
use ReflectionMethod;

use GanbaroDigital\DataContainers\Caches\StaticDataCache;
use GanbaroDigital\Reflection\Exceptions\E4xx_NoSuchClass;
use GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType;

class CallableMethodsList
{
    // we are going to cache the results for performance
    use StaticDataCache;

    /**
     * extract an indexed list of methods from an object
     *
     * @param  object $obj
     *         the object to obtain method names from
     * @return array
     *         a list of the matching method names, indexed by method name
     *         for quick look up
     */
    public static function fromObject($obj)
    {
        // robustness!
        if (!is_object($obj)) {
            throw new E4xx_UnsupportedType(gettype($obj));
        }

        // do we already have the answer?
        if (($retval = self::getObjectFromCache($obj)) !== null) {
            return $retval;
        }

        // what can be called?
        $retval = self::buildListOfObjectMethods($obj);

        // cache it for next time
        self::setObjectInCache($obj, $retval);

        // all done
        return $retval;
    }

    /**
     * extract an indexed list of methods from a class or object
     *
     * @param  string $className
     *         the class to obtain method names from
     * @return array
     *         a list of the matching method names, indexed by method name
     *         for quick look up
     */
    protected static function fromClassName($className)
    {
        // make sure that we have an actual class
        if (!class_exists($className)) {
            throw new E4xx_NoSuchClass($className);
        }

        // do we already have this?
        if (($retval = self::getClassFromCache($className)) !== null) {
            return $retval;
        }

        // what can be called?
        $retval = self::buildListOfClassMethods($className);

        // cache it
        self::setClassInCache($className, $retval);

        // all done
        return $retval;
    }

    /**
     * return a list of public methods on a class
     *
     * this will return both static and non-static methods
     *
     * @param  string $className
     *         the class to check
     * @return array
     */
    private static function getPublicMethodsFromClass($className)
    {
        // get the list of methods from reflection
        $refClass = new ReflectionClass($className);
        return $refClass->getMethods(ReflectionMethod::IS_PUBLIC);
    }

    /**
     * build a list of methods, based on whether they are static or not
     *
     * @param  array $rawMethods
     *         a list of ReflectionMethod objects to filter on
     * @param  boolean $isStatic
     *         TRUE if you want only static methods
     *         FALSE if you want only non-static methods
     * @return array
     *         the method names that have passed the filter
     */
    private static function filterMethodsByStaticness($rawMethods, $isStatic)
    {
        // our return value
        $retval = [];

        // eventually, we'll move this out into a separate class that can
        // be combined as a data stream
        foreach ($rawMethods as $rawMethod) {
            // skip over static methods
            if (!$rawMethod->isStatic() === $isStatic) {
                continue;
            }

            // we like this one :)
            $methodName = $rawMethod->getName();
            $retval[$methodName] = $methodName;
        }

        // all done
        return $retval;
    }

    /**
     * extract an indexed list of methods from a class or object
     *
     * @param  string $className
     *         the class to obtain method names from
     * @return array
     *         a list of the matching method names, indexed by method name
     *         for quick look up
     */
    public static function fromString($className)
    {
        // robustness!
        if (!is_string($className)) {
            throw new E4xx_UnsupportedType(gettype($className));
        }

        return self::fromClassName($className);
    }

    /**
     * extract an indexed list of methods from a class or object
     *
     * @param  mixed $data
     *         the class or object to obtain method names from
     * @return array
     *         a list of the matching method names, indexed by method name
     *         for quick look up
     *
     * @throws E4xx_UnsupportedType
     */
    public static function fromMixed($data)
    {
        // we do this old-skool style because CallableMethodsList (the way
        // we tell everyone to do this kind of matching) actually depends
        // on us to work
        if (is_object($data)) {
            return self::fromObject($data);
        }

        if (is_string($data)) {
            return self::fromString($data);
        }

        // don't know what you are, don't care
        throw new E4xx_UnsupportedType(gettype($data));
    }

    /**
     * extract an indexed list of methods from a class or object
     *
     * @param  mixed $data
     *         the class or object to obtain method names from
     * @return array
     *         a list of the matching method names, indexed by method name
     *         for quick look up
     *
     * @throws E4xx_UnsupportedType
     */
    public function __invoke($data)
    {
        return self::fromMixed($data);
    }

    /**
     * get the cache key to use for a given classname
     *
     * @param  string $className
     *         the class we want to cache data about
     * @return string
     */
    private static function getClassCacheName($className)
    {
        return $className . '::class';
    }

    /**
     * get the cache key to use for a given object
     *
     * @param  object $obj
     *         the object we want to cache data about
     * @return string
     */
    private static function getObjectCacheName($obj)
    {
        return get_class($obj) . '::object';
    }

    private static function getClassFromCache($className)
    {
        $cacheKey = self::getClassCacheName($className);
        return self::getFromCache($cacheKey);
    }

    private static function setClassInCache($className, array $methodsList)
    {
        $cacheKey = self::getClassCacheName($className);
        self::setInCache($cacheKey, $methodsList);
    }

    private static function getObjectFromCache($obj)
    {
        $cacheKey = self::getObjectCacheName($obj);
        return self::getFromCache($cacheKey);
    }

    private static function setObjectInCache($obj, array $methodsList)
    {
        $cacheKey = self::getObjectCacheName($obj);
        self::setInCache($cacheKey, $methodsList);
    }

    private static function buildListOfClassMethods($className)
    {
        // get the methods
        $rawMethods = self::getPublicMethodsFromClass($className);

        // unfortunately, getMethods() returns an array indexed by number,
        // and not an array indexed by method name, so we now need to
        // transform the array
        $retval = self::filterMethodsByStaticness($rawMethods, true);

        // all done
        return $retval;
    }

    private static function buildListOfObjectMethods($obj)
    {
        // get the methods
        $rawMethods = self::getPublicMethodsFromClass(get_class($obj));

        // unfortunately, getMethods() returns an array indexed by number,
        // and not an array indexed by method name, so we now need to
        // transform the array
        $retval = self::filterMethodsByStaticness($rawMethods, false);

        // all done
        return $retval;
    }
}