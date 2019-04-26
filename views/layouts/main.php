<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AdminAsset;
use app\components\H;
use app\components\Helper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use yii\web\YiiAsset;
use yii\widgets\Breadcrumbs;

JqueryAsset::register($this);
AdminAsset::register($this);
YiiAsset::register($this);

$content_base = \Yii::$app->homeUrl . "contents/panel/";
$base         = \Yii::$app->homeUrl;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="utf-8">

    <link rel="icon" type="image/jpg" href="<?= $base ?>anjoman.jpg"/>
	
	<?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
    <style>
        .pagination {
            direction: ltr !important;
        }
    </style>
</head>
<body>
<?php $this->beginBody() ?>

<div id="wrapper">

    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element" style="text-align: center"> <span>
                                <img alt="image" class="img-circle" src="<?= $content_base ?>img/profile_small.png"/>
                                 </span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">
											<?php
											if (Yii::$app->user->isGuest)
												echo 'مهمان';
											else
												echo Yii::$app->user->identity->username;
											?>
										</strong>
                        </a>
                    </div>
                    <div class="logo-element">
                        تله تبادل
                    </div>

                </li>

                <li>
                    <a href="<?= $base . 'index' ?>"><i class="fa fa-home"></i> <span class="nav-label">صفحه اصلی</span></a>
                </li>
				
				<?php if (!Yii::$app->user->isGuest AND Yii::$app->user->identity->type == 10) : ?>
                    <li>
                        <a href="<?= $base . 'bot/index' ?>"><i class="fa fa-android"></i> <span
                                    class="nav-label">مدیریت بات ها</span></a>
                    </li>

                    <li>
                        <a href="<?= $base . 'user/index' ?>"><i class="fa fa-users"></i> <span
                                    class="nav-label">مدیریت کاربران</span></a>
                    </li>

                    <li>
                        <a href="<?= $base . 'channel-violation/index' ?>"><i class="fa fa-warning"></i> <span
                                    class="nav-label">بررسی گزارش ها</span></a>
                    </li>

                    <li>
                        <a href="<?= $base . 'exchange/index' ?>"><i class="fa fa-exchange"></i> <span
                                    class="nav-label">تبادل ها</span></a>
                    </li>

                    <li>
                        <a href="<?= $base . 'exchange-detail/index' ?>"><i class="fa fa-exchange"></i> <span
                                    class="nav-label">جزئیات تبادل ها</span></a>
                    </li>

                    <li>
                        <a href="<?= $base . 'store-log/index' ?>"><i class="fa fa-money"></i> <span
                                    class="nav-label">خریدهای کاربران</span></a>
                    </li>

                    <li>
                        <a href="<?= $base . 'channel/index' ?>"><i class="fa fa-users"></i> <span
                                    class="nav-label">مدیریت کانال ها</span></a>
                    </li>

                    <li>
                        <a href="<?= $base . 'special-exchange/index' ?>"><i class="fa fa-gift"></i> <span
                                    class="nav-label">درخواست های ویژه</span></a>
                    </li>

                    <li>

                        <a href="#"><i class="fa fa-gear"></i> <span class="nav-label">مدیریت تنظیمات</span>
                            <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?= $base . 'option/index?group_id=0' ?>">تنظیمات اصلی</a></li>
                            <li><a href="<?= $base . 'option/index?group_id=1' ?>">تنظیمات سکه ها</a></li>
                            <li><a href="<?= $base . 'option/index?group_id=2' ?>">تنظیمات درخواست ها</a></li>
                        </ul>
                    </li>
				<?php endif; ?>

            </ul>
        </div>
    </nav>

    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i>
                    </a>
                </div>

                <ul class="nav navbar-top-links navbar-right" style="margin-top: 20px;">
                    <li>
                        <span class="m-r-sm text-muted welcome-message">به پنل مدیریت تله تبادل خوش آمدید</span>
                    </li>
                </ul>

                <ul class="nav navbar-top-links navbar-left">

                    <!--<li class="dropdown">
						<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
							<i class="fa fa-envelope"></i> <span class="label label-warning">16</span>
						</a>
						<ul class="dropdown-menu dropdown-messages">
							<li>
								<div class="dropdown-messages-box">
									<a href="profile.html" class="pull-right">
										<img alt="image" class="img-circle" src="img/a4.jpg">
									</a>

									<div class="media-body ">
										<small class="pull-left text-navy">5 ساعت پیش</small>
										<strong>ایمن</strong> لورم ایپسوم <strong>ایمن</strong>. <br>
										<small class="text-muted">دیروز 1:21 ب.ظ - 1394/06/10</small>
									</div>
								</div>
							</li>
							<li class="divider"></li>
							<li>
								<div class="dropdown-messages-box">
									<a href="profile.html" class="pull-right">
										<img alt="image" class="img-circle" src="img/a7.jpg">
									</a>

									<div class="media-body">
										<small class="pull-left">46 ساعت پیش</small>
										<strong>ایمان عباسی</strong> لورم ایپسوم <strong>ایمن</strong>. <br>
										<small class="text-muted">3 روز پیش در 7:58 ب.ظ - 1394/06/10</small>
									</div>
								</div>
							</li>
							<li class="divider"></li>
							<li>
								<div class="dropdown-messages-box">
									<a href="profile.html" class="pull-right">
										<img alt="image" class="img-circle" src="img/profile.jpg">
									</a>

									<div class="media-body ">
										<small class="pull-left">23 ساعت پیش</small>
										<strong>ایمان عباسی</strong> لورم ایپسوم <strong>ایمن</strong>. <br>
										<small class="text-muted">2 وز پیش در 2:30 ق.ظ - 1394/06/10</small>
									</div>
								</div>
							</li>
							<li class="divider"></li>
							<li>
								<div class="text-center link-block">
									<a href="mailbox.html">
										<i class="fa fa-envelope"></i> <strong>مشاهده همه پیغام ها</strong>
									</a>
								</div>
							</li>
						</ul>
					</li>
					<li class="dropdown">
						<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
							<i class="fa fa-bell"></i> <span class="label label-primary">8</span>
						</a>
						<ul class="dropdown-menu dropdown-alerts">
							<li>
								<a href="mailbox.html">
									<div>
										<i class="fa fa-envelope fa-fw"></i> شما 16 پیغام دارید
										<span class="pull-left text-muted small">4 دقیقه پیش</span>
									</div>
								</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="profile.html">
									<div>
										<i class="fa fa-twitter fa-fw"></i> 3 فالوور جدید
										<span class="pull-left text-muted small">12 دقیقه پیش</span>
									</div>
								</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="grid_options.html">
									<div>
										<i class="fa fa-upload fa-fw"></i> سرور ری استارت شد
										<span class="pull-left text-muted small">4 دقیقه پیش</span>
									</div>
								</a>
							</li>
							<li class="divider"></li>
							<li>
								<div class="text-center link-block">
									<a href="notifications.html">
										<strong>مشاهده همه هشدار ها</strong>
										<i class="fa fa-angle-left fa-lg fa-fw"></i>
									</a>
								</div>
							</li>
						</ul>
					</li>-->
					
					<?php if (!Yii::$app->user->isGuest) : ?>
                        <li>
                            <a href="<?= $base ?>logout" data-method="post">
                                <i class="fa fa-sign-out"></i> خروج
                            </a>
                        </li>
					<?php endif; ?>

                </ul>
            </nav>
        </div>

        <div class="row wrapper border-bottom white-bg page-heading">
            <h2><?= Html::encode($this->title) ?></h2>
			<?=
			Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]);
			?>

        </div>

        <div class="wrapper wrapper-content text-wrapper">
            <div style="margin-bottom: 30px">
				<?= $content ?>
            </div>
        </div>

        <div class="footer">
            <div class="pull-left">
                Design And Backend By: <?= Html::a('Behtateam', 'http://behtateam.ir') ?>
            </div>
        </div>

    </div>
</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
