<?php
/**
 * Created by PhpStorm.
 * User: eaglemoor
 * Date: 07.07.15
 * Time: 18:22
 */

namespace yii\maps;


class Path extends \yii\base\Object {

    public $color = '0x0000ff';
    public $weight = 5;

    protected $_points = [];

    public function __construct($config = []) {
        if (isset($config['points']) && is_array($config['points'])) {
            foreach ($config['points'] as $point) {
                $this->addPoint($point);
            }
            unset($config['points']);
        }
        parent::__construct($config);
    }

    public function addPoint(PointInterface $point) {
        $this->_points[] = $point;
    }

    /**
     * @return array|PointInterface[]
     */
    public function getPoints() {
        return $this->_points;
    }

    public function setPoints(array $points) {
        $this->_points = [];
        foreach ($points as $point) {
            $this->addPoint($point);
        }
    }

    public function __toString() {
        $params = [];

        if ($this->color)
            $params[] = 'color:' . $this->color;

        if ($this->weight)
            $params[] = 'weight:' . $this->weight;

        $path = [];
        /** @var PointInterface $point */
        foreach ($this->_points as $point) {
            $path[] = $point->lat() . ',' . $point->lon();
        }
        if (sizeof($path))
            $params[] = implode('|', $path);

        return 'path=' . implode('|', $params);
    }
}