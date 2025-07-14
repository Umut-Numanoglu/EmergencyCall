<?php

/* @var $this yii\web\View */
/* @var $model app\models\Issue */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Update Case: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'All Cases', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="doctor-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="card">
        <div class="card-body">
            <h5>Case Details</h5>
            <p><strong>Patient:</strong> <?= Html::encode($model->patient->getFullName()) ?></p>
            <p><strong>Title:</strong> <?= Html::encode($model->title) ?></p>
            <p><strong>Description:</strong> <?= nl2br(Html::encode($model->description)) ?></p>
        </div>
    </div>

    <div class="mt-4">
        <h5>Update Case Status</h5>
        
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'status')->dropDownList([
            'open' => 'Open',
            'in_progress' => 'In Progress',
            'closed' => 'Closed',
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton('Update Case', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['view', 'id' => $model->id], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div> 