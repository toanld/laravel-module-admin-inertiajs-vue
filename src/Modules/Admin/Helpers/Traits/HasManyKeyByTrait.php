<?php

namespace Modules\Admin\Helpers\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyKeyByTrait
{
    /**
     * @param $keyBy
     * @param $related
     * @param null $foreignKey
     * @param null $localKey
     * @return HasMany
     */
    protected function hasManyKeyBy($keyBy, $related, $foreignKey = null, $localKey = null)
    {
        $instance = $this->newRelatedInstance($related);
        $foreignKey = $foreignKey ?: $this->getForeignKey();
        $localKey = $localKey ?: $this->getKeyName();

        return new \Modules\Admin\Helpers\Classes\HasManyKeyBy($keyBy, $instance->newQuery(),
            $this, $instance->getTable().'.'.$foreignKey, $localKey);
    }
}
