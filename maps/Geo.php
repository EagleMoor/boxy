<?php
/**
 * Created by PhpStorm.
 * User: eaglemoor
 * Date: 03.06.15
 * Time: 10:38
 */

namespace yii\maps;


use app\modules\obd\models\Gnss;
use yii\base\Component;
use yii\helpers\VarDumper;

class Geo extends Component {
    public $language = 'ru';
    public $key;
    public $resultType = 'street_address';

    public $urls = [
        'addressLookup' => 'https://maps.googleapis.com/maps/api/geocode/json?latlng={lat},{lon}&key={key}&language={language}',
        'staticMap' => 'https://maps.googleapis.com/maps/api/staticmap?key={key}&language={language}'
    ];

    public function addressLookup($lat, $lon) {
        $url = str_replace(['{lat}', '{lon}', '{key}', '{language}'], [$lat, $lon, $this->key, $this->language], $this->urls['addressLookup']);
        try {
            $content = file_get_contents($url);
            $content = json_decode($content, true);
        } catch (\Exception $e) {
            \Yii::error(VarDumper::dumpAsString($e), 'geo');
            return false;
        }

        if (isset($content['status']) && $content['status'] == 'OK') {
            if (isset($content['results'][0]['formatted_address'])) {
                return $content['results'][0]['formatted_address'];
            }
        }
        return false;
    }

    public function staticMap($path = null, $markers = null) {
        /** @var Gnss $location */
        /** @var Gnss[] $path */

        $url = [str_replace(['{key}', '{language}'], [$this->key, $this->language], $this->urls['staticMap'])];

        $zoom = 14;
        $size = ['w' => 400, 'h' => 400];

//        if ($location instanceof Gnss) {
//            $url[] = 'center=' . $location->lat . ',' . $location->lon;
//        }

        if (is_array($size)) {
            $url[] = 'size=' . $size['w'] . 'x' . $size['h'];
        }

        $url[] = 'zoom=' . $zoom;

        if (is_array($path)) {
            $lines = ['color:0x0000ff', 'weight:5'];
            foreach ($path as $point) {
                $lines[] = $point->lat . ',' . $point->lon;
            }
            if ($lines) $url[] = 'path=' . implode('|', $lines);

            $markers[] = $path[0];
            if (sizeof($path) > 1) $markers[] = $path[sizeof($path) - 1];
        }

        if ($markers) {
//            $markerMap = 'markers=size:{size}|color:{color}|label:{label}|{lat},{lon}';
            $markerMap = 'markers={lat},{lon}';
            /** @var Gnss $point */
            foreach ($markers as $point) {
                $markersItems = str_replace(['{size}', '{color}', '{label}', '{lat}', '{lon}'], [
                    'normal',
                    'red',
                    'S',
                    $point->lat,
                    $point->lon
                ], $markerMap);

                $url[] = $markersItems;
            }
        }

//        'size' => 'normal',
//                    'color' => '0xFFFFCC',
////                    'label' => null


        return implode('&', $url);
    }
}