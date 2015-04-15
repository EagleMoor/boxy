<?php
/**
 * Created by PhpStorm.
 * User: eaglemoor
 * Date: 15.04.15
 * Time: 12:40
 */

namespace yii\boxy;


class Serializer extends \yii\rest\Serializer {

    /**
     * @inheritdoc
     */
    protected function serializeModel($model) {
        $data = parent::serializeModel($model);

        if (is_array($data)) {
            foreach ($data as &$value) {
                if (is_null($value)) unset($value);
            }
        }

        return $data;
    }
}