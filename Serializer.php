<?php
/**
 * Created by PhpStorm.
 * User: eaglemoor
 * Date: 15.04.15
 * Time: 12:40
 */

namespace yii\boxy;


class Serializer extends \yii\rest\Serializer {
    public $afterSerializeModels;

    /**
     * Serializes a set of models.
     * @param array $models
     * @return array the array representation of the models
     */
    protected function serializeModels(array $models)
    {
//        var_dump($models); die;
        $result = parent::serializeModels($models);
//        var_dump($result); die;

        if ($this->afterSerializeModels) {
            return call_user_func($this->afterSerializeModels, $result);
        }

        return $models;
    }
}