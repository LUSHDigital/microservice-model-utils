<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceModelUtils\Traits\MicroServiceISO8601DatesTrait.
 */

namespace LushDigital\MicroServiceModelUtils\Traits;

use DateTime;

/**
 * Trait intended for use by Lumen models to return timestamps in ISO8601.
 *
 * Note that we are using the DateTime::ATOM constant instead of
 * DateTime::ISO8601 because that isn't actually compatible with ISO-8601...
 *
 * @package LushDigital\MicroServiceModelUtils\Traits
 */
trait MicroServiceISO8601DatesTrait
{
    /**
     * Get the created_at timestamp in ISO8601.
     *
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return DateTime::createFromFormat(self::getDateFormat(), $value)->format(DateTime::ATOM);
    }

    /**
     * Get the created_at timestamp in ISO8601.
     *
     * @param $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return DateTime::createFromFormat(self::getDateFormat(), $value)->format(DateTime::ATOM);
    }
}