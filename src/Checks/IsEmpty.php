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

use GanbaroDigital\Reflection\Maps\MapTypeToMethod;

class IsEmpty
{
    /**
     * check if an item is empty
     *
     * empty means one of:
     * - item itself is empty
     * - item is a data container, and only contains empty data items
     *
     * BE AWARE that this check WILL descend down into the contents of $item
     * until it finds the first piece of non-empty data. This has the potential
     * to be computationally expensive.
     *
     * @param  mixed $item
     *         the item to check
     * @return boolean
     *         TRUE if the item is empty
     *         FALSE otherwise
     */
    public function __invoke($item)
    {
        return self::check($item);
    }

    /**
     * check if an item is empty
     *
     * empty means one of:
     * - item itself is empty
     * - item is a data container, and only contains empty data items
     *
     * BE AWARE that this check WILL descend down into the contents of $item
     * until it finds the first piece of non-empty data. This has the potential
     * to be computationally expensive.
     *
     * @param  mixed $item
     *         the item to check
     * @return boolean
     *         TRUE if the item is empty
     *         FALSE otherwise
     */
    public static function check($item)
    {
        $method = MapTypeToMethod::using($item, self::$dispatchMap);
        return self::$method($item);
    }

    /**
     * a list of the data types that we support, and how to process each
     * supported data type
     *
     * @var array
     */
    private static $dispatchMap = [
        'Array' => 'checkArray',
        'NULL' => 'checkNull',
        'String' => 'checkString',
        'Traversable' => 'checkTraversable'
    ];

    /**
     * called when we have a data type that we do not know how to support
     *
     * @param  mixed $item
     *         the unsupported data
     * @return boolean
     *         always FALSE
     */
    private static function nothingMatchesTheInputType($item)
    {
        // we don't know how to reason about this data type
        //
        // we assume that a false negative will do less harm than
        // a false positive might
        return false;
    }

    /**
     * check if an item is empty
     *
     * empty means one of:
     * - item itself has no content
     * - item is a data container, and only contains empty data items
     *
     * BE AWARE that this check WILL descend down into the contents of $item
     * until it finds the first piece of non-empty data. This has the potential
     * to be computationally expensive.
     *
     * @param  array $item
     *         the item to check
     * @return boolean
     *         TRUE if the item is empty
     *         FALSE otherwise
     */
    private static function checkArray($item)
    {
        if (count($item) === 0) {
            return true;
        }

        return self::checkTraversable($item);
    }

    /**
     * check if an item is empty
     *
     * NULL is always treated as an empty value
     *
     * @param  null $item
     *         the item to check
     * @return boolean
     *         always TRUE
     */
    private static function checkNull($item)
    {
        return true;
    }

    /**
     * check if an item is empty
     *
     * empty means one of:
     * - the string has zero length
     * - the string only contains whitespace
     *
     * @param  string $item
     *         the item to check
     * @return boolean
     *         TRUE if the item is empty
     *         FALSE otherwise
     */
    private static function checkString($item)
    {
        if (trim((string)$item) === '') {
            return true;
        }

        return false;
    }

    /**
     * check if an item is empty
     *
     * empty means one of:
     * - item itself has no content
     * - item is a data container, and only contains empty data items
     *
     * BE AWARE that this check WILL descend down into the contents of $item
     * until it finds the first piece of non-empty data. This has the potential
     * to be computationally expensive.
     *
     * @param  array $item
     *         the item to check
     * @return boolean
     *         TRUE if the item is empty
     *         FALSE otherwise
     */
    private static function checkTraversable($item)
    {
        foreach ($item as $value) {
            if (!self::check($value)) {
                return false;
            }
        }

        // if we get here, item's contents are entirely empty
        return true;
    }
}
