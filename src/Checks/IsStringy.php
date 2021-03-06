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

class IsStringy
{
    /**
     * do we have something that can be used as a read-only string?
     *
     * @param  mixed $item
     *         the item to be checked
     * @return boolean
     *         TRUE if the item can be used as a read-only string
     *         FALSE otherwise
     */
    public static function check($item)
    {
        // general case
        if (is_string($item)) {
            return true;
        }

        // no point in checking non-objects
        if (!is_object($item)) {
            return false;
        }

        // does this class support auto-conversion to a string?
        if (method_exists($item, '__toString')) {
            return true;
        }

        // if we get here, we have run out of ideas
        return false;
    }

    /**
     * do we have something that can be used as a read-only string?
     *
     * @deprecated since 2.10.0
     * @codeCoverageIgnore
     * @param  mixed $item
     *         the item to be checked
     * @return boolean
     *         TRUE if the item can be used as a read-only string
     *         FALSE otherwise
     */
    public static function checkMixed($item)
    {
        return self::check($item);
    }

    /**
     * do we have something that can be used as a read-only string?
     *
     * @param  mixed $item
     *         the item to be checked
     * @return boolean
     *         TRUE if the item can be used as a read-only string
     *         FALSE otherwise
     */
    public function __invoke($item)
    {
        return self::check($item);
    }
}