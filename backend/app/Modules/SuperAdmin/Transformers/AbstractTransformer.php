<?php

namespace App\Modules\SuperAdmin\Transformers;

use Exception;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Primitive;
use League\Fractal\TransformerAbstract;

abstract class AbstractTransformer extends TransformerAbstract
{
    /**
     * @inheritdoc
     *
     * @throws Exception
     */
    public function collection($data, $transformer, $resourceKey = null): Collection
    {
        if (!$resourceKey) {
            throw new Exception('Missing resource key');
        }

        return parent::collection($data, $transformer, $resourceKey);
    }

    /**
     * @inheritdoc
     *
     * @throws Exception
     */
    public function primitive($data, $transformer = null, $resourceKey = null): Primitive
    {
        if (!$resourceKey) {
            throw new Exception('Missing resource key');
        }

        return parent::primitive($data, $transformer, $resourceKey);
    }

    /**
     * @inheritdoc
     *
     * @throws Exception
     */
    public function item($data, $transformer = null, $resourceKey = null): Item
    {
        if (!$resourceKey) {
            throw new Exception('Missing resource key');
        }

        return parent::item($data, $transformer, $resourceKey);
    }
}
