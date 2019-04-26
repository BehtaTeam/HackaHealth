<?php

namespace app\assets;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl  = '@web';
	public $css
					 = [
			'contents/panel/css/bootstrap.min.css',
			'contents/panel/css/bootstrap.rtl.min.css',
			'contents/panel/font-awesome/css/font-awesome.css',
			'contents/panel/css/animate.css',
			'contents/panel/css/style.rtl.css',
		];
	public $js
					 = [
		//	'contents/panel/js/jquery-2.1.1.js',
			'contents/panel/js/bootstrap.min.js',
			'contents/panel/js/plugins/metisMenu/jquery.metisMenu.js',
			'contents/panel/js/plugins/slimscroll/jquery.slimscroll.min.js',
			'contents/panel/js/rada.js',
			'contents/panel/js/plugins/pace/pace.min.js',
		];
	public $depends
					 = [
		];
}
