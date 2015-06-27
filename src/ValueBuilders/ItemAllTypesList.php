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

class ItemAllTypesList
{
    // allows us to calculate once, and then re-use on subsequent
    // repeated calls
    use StaticDataCache;

    /**
     * what type is everything expected to match?
     */
    const FALLBACK_TYPE = "Mixed";

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
        // what are we looking at?
        $simpleType = 'Array';

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
     * get the list of possible types that could match an object
     *
     * @param  object $item
     *         the item to examine
     * @return array
     *         a list of matching objects
     */
    public static function fromObject($item)
    {
        $simpleType = get_class($item);

        // do we have this cached?
        if ($retval = static::getFromCache($simpleType)) {
            return $retval;
        }

        // if we get here, then we're looking at a type that we have not
        // seen before ...

        // our return value
        //
        // we build this to go from the most specific to the least specific
        //
        // 1. parent classes
        // 2. interfaces
        // 3. substituted as a string
        // 4. substituted as a callable
        $retval = [ $simpleType ];

        foreach (class_parents($item) as $parentName) {
            $retval[] = $parentName;
        }

        foreach (class_implements($item) as $interfaceName) {
            $retval[] = $interfaceName;
        }

        // before we see if we can pretend to be other types, let's tell
        // the world that we are, in fact, an object
        $retval[] = "Object";

        // can this object be a string?
        if (method_exists($item, '__toString')) {
            $retval[] = "String";
        }

        if (method_exists($item, '__invoke')) {
            $retval[] = "Callable";
        }

        // add in our fallback type
        $retval[] = static::FALLBACK_TYPE;

        // cache the result
        static::setInCache($simpleType, $retval);

        // all done
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
        if (is_object($item)) {
            return static::fromObject($item);
        }

        // this will pick up callable types too
        if (is_array($item)) {
            return static::fromArray($item);
        }

        // if we get here, then we just return the PHP scalar type
        return [
            ucfirst(gettype($item)),
            static::FALLBACK_TYPE
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