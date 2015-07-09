<?php
/**
 * Created by PhpStorm.
 * User: eaglemoor
 * Date: 07.07.15
 * Time: 17:45
 */

namespace yii\maps;


use yii\base\Object;

class Marker extends Object {
    public $lat;
    public $lon;

    public $label;
    public $size;
    public $color;

    public $icon;
    public $shadow;

    public function __construct($config = []) {
        if ($config instanceof PointInterface) {
            $this->lat = $config->lat();
            $this->lon = $config->lon();
            $config = [];
        }
        parent::__construct($config);
    }

    public function __toString() {
        $params = [];

        $params[] = $this->lat . ',' . $this->lon;

        if ($this->label)
            $params[] = 'label:' . $this->label;

        if ($this->size)
            $params[] = 'size:' . $this->size;

        if ($this->color)
            $params[] = 'color:' . $this->color;

        if (true == $this->shadow)
            $params[] = 'shadow:true';

        return 'markers=' . implode('|', $params);
    }
}