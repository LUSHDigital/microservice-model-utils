<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceModelUtils\Traits\MicroServiceCacheTrait.
 */

namespace LushDigital\MicroServiceModelUtils\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use LushDigital\MicroServiceModelUtils\Models\MicroServiceBaseModel;

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
     * @param Model $model
     * @return array|bool
     */
    public function getModelCacheKeys(Model $model)
    {
        // If this is not a microservice model, bail out.
        if (!($model instanceof MicroServiceBaseModel)) {
            return false;
        }

        // Build list of cache keys we need to clear.
        $cacheKeys = [
            implode(':', [$model->getTable(), 'index']),
        ];

        // If the model has an id field add the cache key.
        if (!empty($model->id)) {
            $cacheKeys[] = implode(':', [$model->getTable(), $model->id]);
        }

        // Add a cache key for each attribute marked as a cache key.
        foreach ($model->getAttributeCacheKeys() as $attributeCacheKey) {
            $cacheKeys[] = implode(':', [$model->getTable(), $attributeCacheKey, $model->{$attributeCacheKey}]);
        }

        return $cacheKeys;
    }

    /**
     * Attempt to forget items from the cache for a given model.
     *
     * @param Model $model
     * @return bool
     */
    public function cacheForget(Model $model)
    {
        // If this is not a microservice model, bail out.
        if (!($model instanceof MicroServiceBaseModel)) {
            return false;
        }

        // Clear the cache for each key.
        foreach ($this->getModelCacheKeys($model) as $cacheKey) {
            Cache::forget($cacheKey);
        }

        return true;
    }
}