<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceModelUtils\Models\MicroServiceBaseModel.
 */

namespace LushDigital\MicroServiceModelUtils\Models;

use Illuminate\Database\Eloquent\Model;
use LushDigital\MicroServiceModelUtils\Contracts\Cacheable;

/**
 * A base model class that can be used in a microservice.
 *
 * @package LushDigital\MicroServiceModelUtils\Models
 */
abstract class MicroServiceBaseModel extends Model implements Cacheable
{
    /**
     * A list of the model attributes that can be used as cache keys.
     *
     * @var array
     */
    protected $attributeCacheKeys = [];

    /**
     * Get the attributes of this model that can be used as cache keys.
     *
     * @return array
     */
    public function getAttributeCacheKeys()
    {
        return $this->attributeCacheKeys;
    }

    /**
     * Set the attributes of this model that can be used as cache keys.
     *
     * @param array $attributeCacheKeys
     * @return $this
     */
    public function setAttributeCacheKeys(array $attributeCacheKeys)
    {
        $this->attributeCacheKeys = $attributeCacheKeys;

        return $this;
    }

    /**
     * Get the table name associated with this model.
     *
     * Allows access to this information without instantiating an object.
     *
     * @return mixed
     */
    public static function getTableName()
    {
        return ((new static)->getTable());
    }

    /**
     * Get the value of the primary key, used to identify this model.
     *
     * @return mixed
     */
    public function getPrimaryKeyValue()
    {
        return $this->id;
    }
}