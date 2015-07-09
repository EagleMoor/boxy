<?php
/**
 * Created by PhpStorm.
 * User: eaglemoor
 * Date: 07.07.15
 * Time: 19:40
 */

namespace yii\maps;


interface PointInterface {
    public function lat();
    public function lon();
}