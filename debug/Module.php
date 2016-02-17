<?php
/**
 * Created by PhpStorm.
 * User: ivakimaki
 * Date: 23.06.15
 * Time: 17:21
 */

namespace yii\boxy_debug;

use yii\web\Response;

class Module extends \yii\base\Module {
    /**
     * @var int Log msg if duration was bigger
     */
    public $timeLimit = 1;
    /**
     * @var string Logging category
     */
    public $logCategory = 'overhead';

//    public function init() {
//
//        if (YII_ENV_DEV) {
//            \Yii::$app->response->on(
//                Response::EVENT_BEFORE_SEND, function () {
//
//                    if ($debug = \Yii::$app->getModule('debug')) {
//                        $debug_id = (isset($debug->logTarget) && isset($debug->logTarget->tag)) ? $debug->logTarget->tag
//                            : 'undefined';
//                    }
//
//                    $duration = \Yii::$app->formatter->asDecimal(microtime(true) - YII_BEGIN_TIME, 4);
//
//                    if ($duration > $this->timeLimit) {
//                        $memory  = sprintf('%.1f MB', memory_get_peak_usage() / 1048576);
//                        \Yii::info("$debug_id    {$duration} s    $memory", $this->logCategory);
//                    }
//
//                    \Yii::$app->response->headers->add('X-Debug-Id', $debug_id);
//                    \Yii::$app->response->headers->add('X-Overhead-Time', $duration);
//                }
//            );
//        }
//
//    }

}