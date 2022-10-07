<?php

namespace Visol\Newsletterregistration\Utility;

use TYPO3\CMS\Core\SingletonInterface;
/*                                                                        *
 * This script belongs to the TYPO3 Flow framework.                       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * A utility class for various algorithms.
 *
 */
class Algorithms implements SingletonInterface
{
    /**
     * Returns a random string with alpha-numeric characters.
     *
     * @param integer $count Number of characters to generate
     * @param string $characters Allowed characters, defaults to alpha-numeric (a-zA-Z0-9)
     * @return string A random string
     * @throws \Exception
     */
    public static function generateRandomString(int $count, string $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'): string
    {
        $characterCount = strlen($characters);
        $string = '';
        for ($i = 0; $i < $count; $i++) {
            $string .= $characters[random_int(0, ($characterCount - 1))];
        }

        return $string;
    }
}
