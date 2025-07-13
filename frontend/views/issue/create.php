<?php

/* @var $this yii\web\View */
/* @var $model common\models\Issue */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Create New Issue';
$this->params['breadcrumbs'][] = ['label' => 'My Issues', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="issue-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="issue-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'priority')->dropDownList([
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'critical' => 'Critical',
        ], ['prompt' => 'Select Priority']) ?>

        <div class="form-group">
            <?= Html::submitButton('Create Issue', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div> 