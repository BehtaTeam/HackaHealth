<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use app\components\Helper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title                   = 'ورود به سامانه';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if (isset($_GET['register'])) : ?>
	<div class="ibox float-e-margins">
		<div class="ibox-content">
			<div class="alert alert-success">
				<?= 'ثبت نام شما با موفقیت انجام شد، لطفا وارد شوید' ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<div class="loginColumns animated fadeInDown">
	<div class="row">

		<div class="col-md-6">
			<h2 class="font-bold">ورود به سامانه</h2>
            

		</div>
		<div class="col-md-6">
			<div class="ibox-content">
				<?php $form = ActiveForm::begin([
					'id'      => 'login-form',
					'method'  => 'post',
					'options' => ['class' => 'frm-login'],
				]); ?>

				<p><?= 'لطفا برای ورود، اطلاعات زیر را وارد کنید:' ?></p>
				<hr>
				<div class="form-group">
					<input <?= $model->hasErrors('email') ? "class=\"error form-control\"" : "class=\"form-control\""; ?>type="text"
						   name="LoginForm[username]" placeholder="<?= 'پست الکترونیکی' ?>">
				</div>
				<?= Html::error($model, 'username'); ?>

				<div class="form-group">
					<input <?= $model->hasErrors('password') ? "class=\"error form-control\"" : "class=\"form-control\""; ?> type="text"
																						  name="LoginForm[password]"
																						  placeholder="<?= 'رمز ورود' ?>">
				</div>
				<?= Html::error($model, 'password'); ?>

				<div class="field">
					<button name="login" class="btn btn-primary block full-width m-b"><?= 'شروع فعالیت' ?><i
							class="ico-left217"></i>
					</button>
				</div>

				<p>حساب کاربری ندارید؟
					<a href="register">ثبت نام</a>
					کنید</p>

				<?php ActiveForm::end(); ?>

			</div>
		</div>
	</div>
</div>

