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
 * @package   Reflection/Requirements
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-reflection
 */

namespace GanbaroDigital\Reflection\Requirements;

use GanbaroDigital\Reflection\Exceptions\E4xx_DataCannotBeEmpty;
use GanbaroDigital\Reflection\Checks\IsEmpty;

class RequireNotEmpty
{
    /**
    * throws exceptions if $item is empty
    *
    * this is a wrapper around our IsEmpty check
     *
     * @param  mixed $itemName
     *         human-readable name of $item
     * @param  mixed $item
     *         the container to check
     * @param  string $exception
     *         the class to use when throwing an exception
     * @return void
     */
    public function __invoke($itemName, $item, $exception = E4xx_DataCannotBeEmpty::class)
    {
        self::check($itemName, $item, $exception);
    }

    /**
     * throws exceptions if $item is empty
     *
     * this is a wrapper around our IsEmpty check
     *
     * @param  string $itemName
     *         human-readable name of $item
     * @param  mixed $item
     *         the data to check
     * @param  string $exception
     *         the class to use when throwing an exception
     * @return void
     */
    public static function check($itemName, $item, $exception = E4xx_DataCannotBeEmpty::class)
    {
        // robustness!
        RequireStringy::check($itemName);

        // make sure that $item is not empty
        if (IsEmpty::check($item)) {
            throw new $exception($itemName);
        }
    }
}
