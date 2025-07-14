<?php

/* @var $this yii\web\View */
/* @var $model common\models\Issue */

use yii\helpers\Html;
use ActiveForm;

$this->title = 'Update Issue: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Manage Issues', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="admin-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="issue-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'priority')->dropDownList([
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'critical' => 'Critical',
        ]) ?>

        <?= $form->field($model, 'status')->dropDownList([
            'open' => 'Open',
            'in_progress' => 'In Progress',
            'closed' => 'Closed',
        ]) ?>

        <?= $form->field($model, 'assigned_doctor_id')->dropDownList(
            \yii\helpers\ArrayHelper::map($doctors, 'id', 'fullName'),
            ['prompt' => 'Select a doctor']
        ) ?>

        <div class="form-group">
            <label>Labels (comma-separated)</label>
            <input type="text" name="labels[]" class="form-control" value="<?= implode(', ', array_map(function($label) { return $label->label; }, $model->labels)) ?>" placeholder="e.g., cardiology, urgent, emergency">
        </div>

        <div class="form-group">
            <?= Html::submitButton('Update Issue', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['view', 'id' => $model->id], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div> 