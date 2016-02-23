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
use stdClass;

final class AllMatchingTypesList extends AllMatchingTypesListCache
{
    /**
     * what type is everything expected to match?
     */
    const FALLBACK_TYPE = "EverythingElse";

    /**
     * the extra items to append to any array's type list
     *
     * @var array
     */
    private static $arrayExtras = [
        'Array',
        'Traversable',
        self::FALLBACK_TYPE
    ];

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
     * the extra items to append to an interface
     * @var array
     */
    private static $interfaceExtras = [
        'Interface',
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
     * @return string[]
     *         a list of matching types
     */
    private static function fromArray($item)
    {
        // our return type
        $retval = [];

        // we go from the most specific to the least specific
        if (is_callable($item)) {
            $retval[] = "Callable";
        }
        $retval = array_merge($retval, self::$arrayExtras);

        // all done
        return $retval;
    }

    /**
     * get the list of possible types that could match a class name
     *
     * @param  string $className
     *         the item to examine
     * @return string[]
     *         a list of matching objects
     */
    private static function fromClass($className)
    {
        // do we have this cached?
        $cacheName = $className . '::class';
        if ($retval = self::getFromCache($cacheName)) {
            return $retval;
        }

        // if we get here, then we're looking at a type that we have not
        // seen before ...
        //
        // combine details about the class name with our fallback types
        $retval = self::buildCombinedClassNameDetails($className);

        // cache the result
        self::setInCache($cacheName, $retval);

        // all done
        return $retval;
    }

    private static function buildCombinedClassNameDetails($className)
    {
        if (class_exists($className)) {
            $retval = array_merge(self::fromClassName($className), self::$classExtras);
        }
        else {
            $retval = array_merge(self::fromClassName($className), self::$interfaceExtras);
        }

        return $retval;
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
        $retval = [$className];

        foreach (class_parents($className) as $parentName) {
            $retval[] = $parentName;
        }

        foreach (class_implements($className) as $interfaceName) {
            $retval[] = $interfaceName;
        }

        return $retval;
        // all done
    }

    /**
     * get the list of possible types that could match an object
     *
     * @param  object $item
     *         the item to examine
     * @return string[]
     *         a list of matching objects
     */
    private static function fromObject($item)
    {
        // do we have this cached?
        $cacheName = self::getObjectCacheName($item);
        if ($retval = self::getFromCache($cacheName)) {
            return $retval;
        }

        // if we get here, then we have not seen this object before
        $retval = self::buildObjectTypeList($item);

        // cache the results
        self::setInCache($cacheName, $retval);

        // all done
        return $retval;
    }

    /**
     * build up a list of supported types for an object
     *
     * @param  object $object
     *         the object to examine
     * @return string[]
     *         the types available
     */
    private static function buildObjectTypeList($object)
    {
        // our details are made up of this order:
        //
        // 1. details about the class
        // 2. that we are an object
        // 3. any magic methods that can be automatically taken advantage of
        // 4. the default fallback type
        $retval = array_merge(
            self::getObjectSpecialTypes($object),
            self::fromClassName(get_class($object)),
            self::getObjectConditionalTypes($object),
            ['Object'],
            [self::FALLBACK_TYPE]
        );

        return $retval;
    }

    /**
     * hard-coded rules for dealing with PHP's built-in classes
     *
     * @param  object $object
     *         object to examine
     * @return array
     */
    private static function getObjectSpecialTypes($object)
    {
        $retval = [];

        if ($object instanceof stdClass) {
            $retval[] = 'Traversable';
        }

        return $retval;
    }

    /**
     * get the list of extra types that are valid for this specific object
     *
     * @param  object $object
     *         the object to examine
     * @return string[]
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
     * what is the cache key to use for this object?
     *
     * @param  object $object
     * @return string
     */
    private static function getObjectCacheName($object)
    {
        return get_class($object) . '::object';
    }

    /**
     * return any data type's type name
     *
     * @param  mixed $item
     *         the item to examine
     * @return string[]
     *         the basic type of the examined item
     */
    public static function from($item)
    {
        $type = ucfirst(gettype($item));
        $methodName = 'from' . $type;
        if (method_exists(self::class, $methodName)) {
            return call_user_func_array([self::class, $methodName], [$item]);
        }

        // if we get here, then we just return the PHP scalar type
        return [
            $type,
            self::FALLBACK_TYPE
        ];
    }

    /**
     * return any data type's type name
     *
     * @deprecated since 2.10.0
     * @codeCoverageIgnore
     * @param  mixed $item
     *         the item to examine
     * @return string[]
     *         the basic type of the examined item
     */
    public static function fromMixed($item)
    {
        return self::from($item);
    }

    /**
     * return any data type's type name
     *
     * @param  mixed $item
     *         the item to examine
     * @return array
     *         the basic type of the examined item
     */
    private static function fromString($item)
    {
        // special case - is this a class name?
        if (class_exists($item)) {
            return self::fromClass($item);
        }

        $retval = [];
        // special case - is this a callable?
        if (is_callable($item)) {
            $retval[] = 'Callable';
        }

        // if we get here, then this is just a plain old regular string
        $retval[] = "String";
        $retval[] = self::FALLBACK_TYPE;
        return $retval;
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
        return self::from($item);
    }
}
