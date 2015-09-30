<?php
/**
 * Created by PhpStorm.
 * User: eaglemoor
 * Date: 07.07.15
 * Time: 17:48
 */
namespace yii\maps\google;

use yii\maps\PointInterface;

class StaticMaps extends \yii\base\Object
{
    public $url = 'https://maps.googleapis.com/maps/api/staticmap?key={key}&language={language}';

    public $width = 400;
    public $height = 400;

    public $zoom = 14;

    /** @var PointInterface */
    protected $_center;

    /** @var array Path */
    protected $_paths = [];

    /** @var array Marker */
    protected $_markers = [];

    public $key;
    public $language;

    public function addMarket(\yii\maps\Marker $marker)
    {
        $this->_markers[] = $marker;
    }

    /**
     * @return array|\yii\maps\Marker[]
     */
    public function getMarkers()
    {
        return $this->_markers;
    }

    /**
     * @param \yii\maps\Marker[] $markers
     */
    public function setMarkers(array $markers)
    {
        $this->_markers = [];
        foreach ($markers as $marker) {
            $this->addMarket($marker);
        }
    }

    /**
     * @param \yii\maps\Path $path
     */
    public function addPath(\yii\maps\Path $path)
    {
        $this->_paths[] = $path;
    }

    /**
     * @return array|\yii\maps\Path[]
     */
    public function getPaths()
    {
        return $this->_paths;
    }

    /**
     * @param array|\yii\maps\Path[] $paths
     */
    public function setPaths(array $paths)
    {
        $this->_paths = [];
        foreach ($paths as $path) {
            $this->addPath($path);
        }
    }

    /**
     * @param PointInterface $point
     */
    public function setCenter(PointInterface $point)
    {
        $this->_center = $point;
    }

    /**
     * @return PointInterface
     */
    public function getCenter()
    {
        return $this->_center;
    }

    public function buildUrl()
    {
        $url = str_replace(['{key}', '{language}'], [$this->key, $this->language], $this->url);

        $params = [];

        $params[] = 'size=' . $this->width . 'x' . $this->height;

        if ($this->getMarkers()) {
            foreach ($this->getMarkers() as $marker) {
                $params[] = (string)$marker;
            }
        }

        if ($this->getPaths()) {
            foreach ($this->getPaths() as $path) {
                $params[] = (string)$path;
            }
        }

        if ($this->getCenter()) {
            $params[] = 'center=' . $this->getCenter()->lat() . ',' . $this->getCenter()->lon();
        }

        if ($this->zoom)
            $params[] = 'zoom=' . $this->zoom;

        return $url . '&' . implode('&', $params);
    }

    public function __toString()
    {
        return $this->buildUrl();
    }
}