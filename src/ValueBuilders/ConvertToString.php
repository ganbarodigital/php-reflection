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
 * @link      http://code.ganbarodigital.com/php-reflection
 */

namespace GanbaroDigital\Reflection\ValueBuilders;

class ConvertToString
{
    use LookupMethodByType;

    /**
     * convert any input type into a real string
     *
     * @param  mixed $item
     *         the data to convert
     * @return string
     */
    public function __invoke($item)
    {
        return self::from($item);
    }

    /**
     * convert any input type into a real string
     *
     * @param  mixed $item
     *         the data to convert
     * @return string
     */
    public static function from($item)
    {
        $method = self::lookupMethodFor($item, self::$dispatchTable);
        return self::$method($item);
    }

    /**
     * convert an array into a string
     *
     * @param  array $item
     *         the data to convert
     * @return string
     */
    private static function fromArray($item)
    {
        return implode(" ", $item);
    }

    /**
     * convert a boolean value into a string
     *
     * @param  boolean $item
     *         the data to convert
     * @return string
     *         either 'true' or 'false'
     */
    private static function fromBoolean($item)
    {
        if ($item) {
            return "true";
        }
        return "false";
    }

    /**
     * convert a callable into a string
     *
     * @param  callable $item
     *         the data to convert
     * @return string
     */
    private static function fromCallable($item)
    {
        $callable_text = '';
        is_callable($item, false, $callable_text);
        return "(callable {$callable_text}())";
    }

    /**
     * convert NULL into a string
     *
     * @return string
     *         always 'null'
     */
    private static function fromNULL()
    {
        return "null";
    }

    /**
     * convert an object into a string
     *
     * @param  object $item
     *         the data to convert
     * @return string
     */
    private static function fromObject($item)
    {
        // general case
        return "(object of type " . get_class($item) . ")";
    }

    /**
     * convert a resource into a string
     *
     * @return string
     */
    private static function fromResource()
    {
        return "(resource)";
    }

    /**
     * convert a string into a string
     *
     * not quite as daft as it sounds, as $item could be a data type that
     * can be coerced into being a string
     *
     * @param  mixed $item
     *         the data to convert
     * @return string
     */
    private static function fromString($item)
    {
        return (string)$item;
    }

    /**
     * our list of which method to call for which data type
     * @var array
     */
    private static $dispatchTable = [
        "Array" => "fromArray",
        "Boolean" => "fromBoolean",
        "Callable" => "fromCallable",
        "Double" => "fromString",
        "Integer" => "fromString",
        "NULL" => "fromNull",
        "Object" => "fromObject",
        "Resource" => "fromResource",
        "String" => "fromString",
    ];
}