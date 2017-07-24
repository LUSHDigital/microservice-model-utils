<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceModelUtils\Traits\MicroServiceCacheTrait.
 */

namespace LushDigital\MicroServiceModelUtils\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use LushDigital\MicroServiceModelUtils\Contracts\Cacheable;

/**
 * A trait for handling caching in a microservice.
 *
 * @package LushDigital\MicroServiceModelUtils\Traits
 */
trait MicroServiceCacheTrait
{
    /**
     * Get an array of possible cache keys for the given model.
     *
     * @param Cacheable $model
     * @return array|bool
     */
    public function getModelCacheKeys(Cacheable $model)
    {
        // Build list of cache keys we need to clear.
        $cacheKeys = [
            implode(':', [$model->getTable(), 'index']),
        ];

        // If the model has an primary key add the cache key.
        if (!empty($model->getPrimaryKeyValue())) {
            $cacheKeys[] = implode(':', [$model->getTable(), $model->getPrimaryKeyValue()]);
        }

        // Add the cache keys for the model attributes.
        $this->addAttributeCacheKeys($cacheKeys, $model);

        return $cacheKeys;
    }

    /**
     * Attempt to forget items from the cache for a given model.
     *
     * @param Cacheable $model
     * @return bool
     */
    public function cacheForget(Cacheable $model)
    {
        // Clear the cache for each key.
        foreach ($this->getModelCacheKeys($model) as $cacheKey) {
            Cache::forget($cacheKey);
        }

        return true;
    }

    /**
     * Add cache keys for each attribute of a given model.
     *
     * @param array $cacheKeys
     * @param Cacheable $model
     */
    protected function addAttributeCacheKeys(array &$cacheKeys, Cacheable $model)
    {
        // Add a cache key for each attribute marked as a cache key.
        foreach ($model->getAttributeCacheKeys() as $attributeCacheKey) {
            $origAttributeValue = $this->getOriginalCacheKeyValue($model, $attributeCacheKey);

            $attributesValues = [];
            if ($origAttributeValue != null) {
                array_push($attributesValues, $origAttributeValue);
            }

            array_push($attributesValues, $model->{$attributeCacheKey});

            foreach ($attributesValues as $attributeValue) {
                // If the attribute is a collection check each item value.
                if ($attributeValue instanceof Collection) {
                    $this->getCollectionAttributeCacheKeys($cacheKeys, $model, $attributeCacheKey, $attributeValue);
                } elseif (is_scalar($attributeValue)) {
                    // Otherwise just get the value.
                    $cacheKeys[] = implode(':', [$model->getTable(), $attributeCacheKey, $attributeValue]);
                }
            }
        }
    }

    /**
     * Get the attribute cache keys from a collection attribute.
     *
     * @param array $cacheKeys
     * @param Cacheable $model
     * @param $attribute
     * @param $collection
     */
    protected function getCollectionAttributeCacheKeys(array &$cacheKeys, Cacheable $model, $attribute, $collection)
    {
        foreach ($collection as $item) {
            if (isset($item->id)) {
                $cacheKeys[] = implode(':', [$model->getTable(), $attribute, $item->id]);
            }
        }
    }

    /**
     * Get original cache key value.
     *
     * @param Cacheable $model
     * @param $attributeCacheKey
     * @return mixed|null
     */
    protected function getOriginalCacheKeyValue(Cacheable $model, $attributeCacheKey)
    {
        // Bail out if the model is not an eloquent model.
        if (!$model instanceof Model) {
            return null;
        }

        return empty($model->getOriginal($attributeCacheKey)) ? null : $model->getOriginal($attributeCacheKey);
    }
}
