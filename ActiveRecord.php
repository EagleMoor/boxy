<?php
/**
 * Created by PhpStorm.
 * User: eaglemoor
 * Date: 14.04.15
 * Time: 11:40
 */

namespace yii\boxy;

class ActiveRecord extends \yii\db\ActiveRecord {
    public $attributeMap = [];

    protected function getAttributeMap()
    {
        return array_merge($this->fields(), $this->extraFields(), $this->attributeMap);
    }

    public function addError($attribute, $error = '' )
    {
        $key = array_search($attribute, $this->getAttributeMap());
        if (false !== $key && is_string($key)) {
            $attribute = $key;
        }
        parent::addError($attribute, $error);
    }

    public function setAttributes($values, $safeOnly = true)
    {
        $attr = $this->getAttributeMap();
        foreach ($values as $key => $value) {
            if (array_key_exists($key, $attr) && is_string($attr[$key])) {
                $values[$attr[$key]] = $value;
            }
        }

        parent::setAttributes($values, $safeOnly);
    }
}