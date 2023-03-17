<?php

/*
 * Copyright (c) 2023, Sascha Greuel and Contributors
 *
 * Permission to use, copy, modify, and/or distribute this software for any
 * purpose with or without fee is hereby granted, provided that the above
 * copyright notice and this permission notice appear in all copies.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
 * WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
 * ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
 * WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
 * ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
 * OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 */

namespace SoftCreatR\OpenAI\Exception;

use Exception;
use Throwable;

use const JSON_ERROR_NONE;
use const JSON_THROW_ON_ERROR;

/**
 * Represents an exception thrown by the OpenAI API client.
 */
class OpenAIException extends Exception
{
    /**
     * Creates a new OpenAIException instance.
     *
     * @param string $message The exception message. If it's a valid JSON string, the "error.message" value will be used.
     * @param int $code The exception code.
     * @param Throwable|null $previous The previous exception used for the exception chaining.
     */
    public function __construct($message = "", $code = 0, ?Throwable $previous = null)
    {
        try {
            if (
                \is_string($message)
                && \is_array(\json_decode($message, true, 512, JSON_THROW_ON_ERROR))
                && (\json_last_error() === JSON_ERROR_NONE)
            ) {
                $decoded = \json_decode($message, true, 512, JSON_THROW_ON_ERROR);
                $message = $decoded['error']['message'] ?? $decoded;
            }
        } catch (Exception $e) {
            // ignore
        }

        parent::__construct($message, $code, $previous);
    }
}
