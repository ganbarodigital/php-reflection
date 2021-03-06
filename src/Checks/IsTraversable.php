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

use stdClass;
use IteratorAggregate;
use Traversable;
use GanbaroDigital\DataContainers\Caches\StaticDataCache;
use GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList;

class IsTraversable
{
    // speed repeated things up
    use StaticDataCache;

    /**
     * list of types that we consider to be traversable
     *
     * @var array
     */
    private static $acceptableTypes = [
        'Array'                  => true,
        stdClass::class          => true,
        Traversable::class       => true,
        IteratorAggregate::class => true,
    ];

    /**
     * is $item something that can be used in a foreach() loop?
     *
     * @param  mixed $item
     *         the item to examine
     * @return boolean
     *         true if the item can be used in a foreach() loop
     *         false otherwise
     */
    public static function check($item)
    {
        if (($retval = self::getCachedResult($item)) !== null) {
            return $retval;
        }

        $retval = self::calculateResult($item);

        self::setCachedResult($item, $retval);
        return $retval;
    }

    /**
     * is $item something that can be used in a foreach() loop?
     *
     * @param  mixed $item
     *         the item to examine
     * @return boolean
     *         true if the item can be used in a foreach() loop
     *         false otherwise
     */
    private static function calculateResult($item)
    {
        $itemTypes = AllMatchingTypesList::from($item);
        foreach ($itemTypes as $itemType) {
            if (isset(self::$acceptableTypes[$itemType])) {
                return true;
            }
        }

        return false;
    }

    /**
     * is $item something that can be used in a foreach() loop?
     *
     * @deprecated since 2.10.0
     * @codeCoverageIgnore
     * @param  mixed $item
     *         the item to examine
     * @return boolean
     *         true if the item can be used in a foreach() loop
     *         false otherwise
     */
    public static function checkMixed($item)
    {
        return self::check($item);
    }

    /**
     * is $item something that can be used in a foreach() loop?
     *
     * @param  mixed $item
     *         the item to examine
     * @return boolean
     *         true if the item can be used in a foreach() loop
     *         false otherwise
     */
    public function __invoke($item)
    {
        return self::check($item);
    }

    /**
     * have we seen this kind of item before?
     *
     * @param  mixed $item
     * @return boolean|null
     */
    private static function getCachedResult($item)
    {
        $cacheKey = self::getCacheKey($item);
        return self::getFromCache($cacheKey);
    }

    /**
     * remember the result in case there is a next time
     *
     * @param mixed $item
     * @param boolean $result
     * @return void
     */
    private static function setCachedResult($item, $result)
    {
        $cacheKey = self::getCacheKey($item);
        self::setInCache($cacheKey, $result);
    }

    /**
     * work out what lookup key to use for this item
     *
     * @param  mixed $item
     * @return string
     */
    private static function getCacheKey($item)
    {
        if (is_object($item)) {
            return get_class($item);
        }

        return gettype($item);
    }
}