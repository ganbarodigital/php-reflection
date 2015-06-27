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

use GanbaroDigital\Reflection\Caches\AllMatchingTypesListCache;
use GanbaroDigital\Reflection\Exceptions\E4xx_NoSuchClass;
use GanbaroDigital\Reflection\Exceptions\E4xx_UnsupportedType;

class AllMatchingTypesList extends AllMatchingTypesListCache
{
    /**
     * what type is everything expected to match?
     */
    const FALLBACK_TYPE = "Mixed";

    /**
     * the extra items to append to a class
     * @var array
     */
    private static $classExtras = [
        'Class',
        'String',
        self::FALLBACK_TYPE
    ];

    /**
     * the extra items that *might* be part of an object's type list
     * @var array
     */
    private static $objectConditionalExtras = [
        "__toString" => "String",
        "__invoke"   => "Callable",
    ];

    /**
     * get the list of possible types that could match an array
     *
     * @param  array $item
     *         the item to examine
     * @return array
     *         a list of matching types
     */
    public static function fromArray($item)
    {
        // robustness!
        if (!is_array($item)) {
            throw new E4xx_UnsupportedType(gettype($item));
        }

        // our return type
        $retval = [];

        // we go from the most specific to the least specific
        if (is_callable($item)) {
            $retval[] = "Callable";
        }
        $retval[] = "Array";
        $retval[] = "Traversable";
        $retval[] = static::FALLBACK_TYPE;

        // all done
        return $retval;
    }

    /**
     * get the list of possible types that could match a class name
     *
     * @param  string $className
     *         the item to examine
     * @return array
     *         a list of matching objects
     */
    public static function fromClass($className)
    {
        // robustness!
        self::checkAcceptableClassName($className);

        // do we have this cached?
        $cacheName = $className . '::class';
        if ($retval = self::getFromCache($cacheName)) {
            return $retval;
        }

        // if we get here, then we're looking at a type that we have not
        // seen before ...
        //
        // combine details about the class name with our fallback types
        $retval = array_merge(self::fromClassName($className), self::$classExtras);

        // cache the result
        static::setInCache($cacheName, $retval);

        // all done
        return $retval;
    }

    /**
     * have we been given something that's really a classname?
     *
     * @param  string $className
     *         the class name to check
     * @return void
     *
     * @throws E4xx_UnsupportedType
     * @throws E4xx_NoSuchClass
     */
    private static function checkAcceptableClassName($className)
    {
        // robustness!
        if (!is_string($className)) {
            throw new E4xx_UnsupportedType(gettype($className), 2);
        }

        // make sure we have a safe input
        if (!class_exists($className)) {
            throw new E4xx_NoSuchClass($className);
        }
    }

    /**
     * get the list of possible types that could match a class name
     *
     * caching the result is the responsibility of the caller
     *
     * @param  string $className
     *         the item to examine
     * @return array
     *         a list of matching objects
     */
    private static function fromClassName($className)
    {
        // our return value
        //
        // we build this to go from the most specific to the least specific
        //
        // 1. parent classes
        // 2. interfaces
        // 3. substituted as a string
        // 4. substituted as a callable
        $retval = [ $className ];

        foreach (class_parents($className) as $parentName) {
            $retval[] = $parentName;
        }

        foreach (class_implements($className) as $interfaceName) {
            $retval[] = $interfaceName;
        }

        // all done
        return $retval;
    }

    /**
     * get the list of possible types that could match an object
     *
     * @param  object $item
     *         the item to examine
     * @return array
     *         a list of matching objects
     */
    public static function fromObject($item)
    {
        // robustness!
        self::checkAcceptableObject($item);

        // what kind of object are we looking at?
        $className = get_class($item);

        // do we have this cached?
        $cacheName = $className . '::object';
        if ($retval = static::getFromCache($cacheName)) {
            return $retval;
        }

        // if we get here, then we have not seen this object before
        //
        // our details are made up of this order:
        //
        // 1. details about the class
        // 2. that we are an object
        // 3. any magic methods that can be automatically taken advantage of
        // 4. the default fallback type
        $retval = array_merge(
            self::fromClassName($className),
            [ 'Object'],
            self::getObjectConditionalTypes($item),
            [ self::FALLBACK_TYPE ]
        );

        // cache the results
        static::setInCache($cacheName, $retval);

        // all done
        return $retval;
    }

    /**
     * make sure that we have an object to inspect
     *
     * @param  mixed $item
     *         the item to inspect
     * @return void
     *
     * @throws E4xx_UnsupportedType
     */
    private static function checkAcceptableObject($item)
    {
        // robustness!
        if (!is_object($item)) {
            throw new E4xx_UnsupportedType(gettype($item), 2);
        }
    }

    /**
     * get the list of extra types that are valid for this specific object
     *
     * @param  object $object
     *         the object to examine
     * @return array
     *         a (possibly empty) list of types for this object
     */
    private static function getObjectConditionalTypes($object)
    {
        $retval = [];

        foreach (self::$objectConditionalExtras as $methodName => $type) {
            if (method_exists($object, $methodName)) {
                $retval[] = $type;
            }
        }

        return $retval;
    }

    /**
     * return any data type's type name
     *
     * @param  mixed $item
     *         the item to examine
     * @return array
     *         the basic type of the examined item
     */
    public static function fromMixed($item)
    {
        $type = ucfirst(gettype($item));
        $methodName = 'from' . ucfirst($type);
        if (method_exists(static::class, $methodName)) {
            return call_user_func_array([static::class, $methodName], [$item]);
        }

        // if we get here, then we just return the PHP scalar type
        return [
            $type,
            static::FALLBACK_TYPE
        ];
    }

    /**
     * return any data type's type name
     *
     * @param  mixed $item
     *         the item to examine
     * @return array
     *         the basic type of the examined item
     */
    public static function fromString($item)
    {
        // robustness!
        if (!is_string($item)) {
            throw new E4xx_UnsupportedType(gettype($item));
        }

        // special case - is this a class name?
        if (class_exists($item)) {
            return self::fromClass($item);
        }

        // if we get here, then this is just a plain old regular string
        return [
            "String",
            Static::FALLBACK_TYPE,
        ];
    }

    /**
     * return any data type's type name list
     *
     * @param  mixed $item
     *         the item to examine
     * @return array
     *         the list of type(s) that this item can be
     */
    public function __invoke($item)
    {
        return static::fromMixed($item);
    }
}