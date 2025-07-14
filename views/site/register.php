<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var common\models\RegisterForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Register';
?>
<div class="site-register">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <h1><?= Html::encode($this->title) ?></h1>

            <p>Please fill out the following fields to register:</p>

            <?php $form = ActiveForm::begin(['id' => 'register-form']); ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'first_name')->textInput(['autofocus' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'last_name')->textInput() ?>
                </div>
            </div>

            <?= $form->field($model, 'username')->textInput() ?>

            <?= $form->field($model, 'email')->textInput() ?>

            <?= $form->field($model, 'phone')->textInput() ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'confirmPassword')->passwordInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Register', ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <div class="mt-3">
                <p>Already have an account? <a href="<?= \yii\helpers\Url::to(['site/login']) ?>">Login here</a></p>
            </div>
        </div>
    </div>
</div> 