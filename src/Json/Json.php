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

use JsonException;
use function json_encode;
use function sprintf;

/**
 * @internal
 */
final class Json
{
    private const ENCODE_FLAGS = JSON_THROW_ON_ERROR
                                | JSON_INVALID_UTF8_SUBSTITUTE
                                | JSON_UNESCAPED_UNICODE
                                | JSON_UNESCAPED_SLASHES;

    /**
     * @param array<string, mixed> $value
     *
     * @throws EncodingException
     */
    public function encode(array $value): string
    {
        try {
            return json_encode($value, self::ENCODE_FLAGS);
        } catch (JsonException $e) {
            throw new EncodingException(
                sprintf('Unable to encode payload to JSON: %s', $e->getMessage()), 0, $e
            );
        }
    }
}
