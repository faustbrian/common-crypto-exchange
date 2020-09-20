<?php

declare(strict_types=1);

/*
 * This file is part of Common Crypto Exchange.
 *
 * (c) KodeKeep <hello@kodekeep.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KodeKeep\CommonCryptoExchange\Helpers;

/**
 * Undocumented class.
 */
final class ResolveScientificNotation
{
    /**
     * Undocumented function.
     *
     * @param float $float
     *
     * @return string
     */
    public static function execute(float $float): string
    {
        $parts = explode('E', strtoupper($float));

        if (count($parts) === 2) {
            $exp     = abs(end($parts)) + strlen($parts[0]);
            $decimal = number_format($float, $exp);

            return strval(rtrim($decimal, '.0'));
        }

        return strval($float);
    }
}
