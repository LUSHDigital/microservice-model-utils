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
        return DateTime::createFromFormat(self::getDateFormat(), $value)->format(DateTime::ISO8601);
    }

    /**
     * Get the created_at timestamp in ISO8601.
     *
     * @param $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return DateTime::createFromFormat(self::getDateFormat(), $value)->format(DateTime::ISO8601);
    }
}