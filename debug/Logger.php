<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\boxy_debug;

use Yii;
use yii\base\Component;

class Logger extends \yii\log\Logger
{
	/**
	 * Flushes log messages from memory to targets.
	 * @param boolean $final whether this is a final call during a request.
	 */
	public function flush($final = false)
	{
		if($final){
			$duration = \Yii::$app->formatter->asDecimal(microtime(true) - YII_BEGIN_TIME, 4);


			$debug_id = '';

			if ($debug = \Yii::$app->getModule('debug')) {
				$debug_id = (isset($debug->logTarget) && isset($debug->logTarget->tag)) ? $debug->logTarget->tag
					: 'undefined';
			}
			$memory  = sprintf('%.1f MB', memory_get_peak_usage() / 1048576);
			\Yii::info( implode(
				'   ',
				[
					$debug_id,
					Yii::$app->request->method,
					Yii::$app->request->url,
					$duration,
					$memory,
				]
			), 'overhead');
		}

		parent::flush($final);

	}
}
