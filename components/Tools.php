<?php
namespace app\components;

use yii\helpers\VarDumper;

class Tools
{
	public static function debug($var, $exit = false)
	{
		echo '<pre align="left" dir="ltr" style="direction:ltr;text-align:left;">' . PHP_EOL;
		echo VarDumper::dump($var, 10, true) . PHP_EOL;
		echo '</pre>' . PHP_EOL;
		if ($exit) {
			Yii::app()->end();
		}
	}
}