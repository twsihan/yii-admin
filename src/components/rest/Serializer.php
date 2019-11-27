<?php

namespace twsihan\admin\components\rest;

use twsihan\admin\components\base\EntityModel;
use Yii;
use yii\base\Arrayable;
use yii\base\Model;
use yii\data\DataProviderInterface;
use yii\data\Pagination;

/**
 * Class Serializer
 *
 * @package app\components\rest
 * @author twsihan <twsihan@gmail.com>
 */
class Serializer extends \yii\rest\Serializer
{
    /**
     * @var EntityModel
     */
    public $modelClass;


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if ($this->modelClass && !$this->modelClass instanceof EntityModel) {
            $this->modelClass = Yii::createObject($this->modelClass);
        }
    }

    /**
     * Serializes the given data into a format that can be easily turned into other formats.
     * This method mainly converts the objects of recognized types into array representation.
     * It will not do conversion for unknown object types or non-object data.
     * The default implementation will handle [[Model]] and [[DataProviderInterface]].
     * You may override this method to support more object types.
     * @param mixed $data the data to be serialized.
     * @return mixed the converted data.
     */
    public function serialize($data)
    {
        if ($data instanceof Model && $data->hasErrors()) {
            return $this->serializeModelErrors($data);
        } elseif ($data instanceof Arrayable) {
            $data = $this->serializeModel($data);
            return $this->serializeHandle($data);
        } elseif ($data instanceof DataProviderInterface) {
            $data = $this->serializeDataProvider($data);
            return $this->serializeHandle($data);
        }
        return $this->serializeHandle($data);
    }

    protected function serializeHandle($data)
    {
        $modelClass = $this->modelClass;
        if ($modelClass && is_callable($modelClass->getSerializeHandle())) {
            $data = call_user_func($modelClass->getSerializeHandle(), $data);
        }
        return $data;
    }

    /**
     * @param Pagination $pagination
     * @return array
     */
    protected function serializePagination($pagination)
    {
        return [
            'totalCount' => $pagination->totalCount,
            'pageCount' => $pagination->getPageCount(),
            'currentPage' => $pagination->getPage() + 1,
            'perPage' => $pagination->getPageSize(),
        ];
    }
}
