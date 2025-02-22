<?php declare(strict_types=1);

/*
 * This file is part of the Status Stack package.
 *
 * (c) Lukas Hron <info@lukashron.cz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StatusStack\Json;

final class Json
{
    public static function encode(array $value): string
    {
        $json = json_encode($value);

        if ($error = json_last_error()) {
            throw new JsonException(json_last_error_msg(), $error);
        }

        return $json;
    }
}