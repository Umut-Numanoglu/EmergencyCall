<?php

/** @var yii\web\View $this */
/** @var app\models\Issue $model */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Update Emergency Call: ' . $model->title;
?>
<div class="issue-update">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('View', ['view', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Back to List', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Update Emergency Call</h5>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'priority')->dropDownList([
                        'low' => 'Low',
                        'medium' => 'Medium', 
                        'high' => 'High',
                        'critical' => 'Critical'
                    ]) ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div> 