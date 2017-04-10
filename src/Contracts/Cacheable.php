<?php
/**
 * @file
 * Contains LushDigital\MicroServiceModelUtils\Contracts\Cacheable.
 */

namespace LushDigital\MicroServiceModelUtils\Contracts;

/**
 * A contract to indicate the output of a class is cacheable.
 *
 * @package LushDigital\MicroServiceModelUtils\Contracts
 */
interface Cacheable
{
    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable();

    /**
     * Get the attributes of this model that can be used as cache keys.
     *
     * @return array
     */
    public function getAttributeCacheKeys();
}