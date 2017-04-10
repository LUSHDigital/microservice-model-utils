<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceModelUtils\Traits\MicroServiceCacheTrait.
 */

namespace LushDigital\MicroServiceModelUtils\Traits;

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
}